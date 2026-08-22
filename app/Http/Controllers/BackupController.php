<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    private $backupFilename = 'mims_backup.sql';
    
    public function index()
    {
        $backups = $this->getBackups();
        return view('backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            $path = storage_path('app/backups');
            
            // Create directory if not exists
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
            
            $fullPath = $path . '/' . $this->backupFilename;
            
            // Delete old backup if exists
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
            
            // Database credentials
            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbName = env('DB_DATABASE', 'material_inventory');
            $dbUser = env('DB_USERNAME', 'root');
            $dbPassword = env('DB_PASSWORD', '');
            
            // Run mysqldump
            $command = "mysqldump -u {$dbUser}";
            if ($dbPassword) {
                $command .= " -p'{$dbPassword}'";
            }
            $command .= " {$dbName} > {$fullPath}";
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && File::exists($fullPath)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Backup updated successfully!',
                    'filename' => $this->backupFilename,
                    'size' => $this->formatSize(File::size($fullPath)),
                    'date' => date('d/m/Y H:i:s')
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Backup failed. Check database credentials.'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (File::exists($path)) {
            return response()->download($path, 'mims_database_backup_' . date('Y-m-d_His') . '.sql');
        }
        
        return redirect()->back()->with('error', 'Backup file not found.');
    }

    public function delete($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (File::exists($path)) {
            File::delete($path);
            return response()->json(['success' => true, 'message' => 'Backup deleted!']);
        }
        
        return response()->json(['success' => false, 'message' => 'File not found'], 404);
    }

    private function getBackups()
    {
        $path = storage_path('app/backups');
        $backups = [];
        
        if (File::exists($path)) {
            $files = File::files($path);
            
            foreach ($files as $file) {
                // Only show .sql files
                if ($file->getExtension() === 'sql') {
                    $backups[] = [
                        'filename' => $file->getFilename(),
                        'size' => $this->formatSize($file->getSize()),
                        'date' => date('d/m/Y H:i:s', $file->getMTime()),
                        'timestamp' => $file->getMTime(),
                    ];
                }
            }
            
            usort($backups, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });
        }
        
        return $backups;
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}
