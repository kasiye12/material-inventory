<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityLogger
{
    /**
     * Log an activity
     */
    public static function log($actionType, $description, $documentType = null, $documentId = null, $documentName = null, $module = null, $oldValues = null, $newValues = null, $locationId = null, $locationName = null)
    {
        try {
            $user = Auth::user();
            $request = request();
            
            $pcName = self::getPcName();
            $browser = self::getBrowser();
            $os = self::getOperatingSystem();
            
            return ActivityLog::create([
                'user_id' => $user ? $user->id : null,
                'user_name' => $user ? $user->name : 'System',
                'user_email' => $user ? $user->email : null,
                'user_role' => $user ? ($user->roles->first()->name ?? null) : null,
                'action_type' => $actionType,
                'action_description' => $description,
                'document_type' => $documentType,
                'document_id' => $documentId,
                'document_name' => $documentName,
                'module' => $module,
                'ip_address' => $request ? $request->ip() : null,
                'pc_name' => $pcName,
                'user_agent' => $request ? $request->userAgent() : null,
                'browser' => $browser,
                'operating_system' => $os,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'location_id' => $locationId,
                'location_name' => $locationName,
            ]);
        } catch (\Exception $e) {
            // Log error but don't crash the application
            \Log::error('ActivityLogger error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get PC name from user agent
     */
    private static function getPcName()
    {
        $userAgent = request()->userAgent();
        
        if (preg_match('/Windows NT ([0-9.]+)/', $userAgent, $matches)) {
            return 'Windows PC (NT ' . $matches[1] . ')';
        } elseif (strpos($userAgent, 'Macintosh') !== false) {
            return 'Mac Computer';
        } elseif (strpos($userAgent, 'Linux') !== false && strpos($userAgent, 'Android') === false) {
            return 'Linux Computer';
        } elseif (strpos($userAgent, 'Android') !== false) {
            return 'Android Device';
        } elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
            return 'iOS Device';
        }
        
        return 'Unknown Device';
    }

    /**
     * Get browser name
     */
    private static function getBrowser()
    {
        $userAgent = request()->userAgent();
        
        if (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Edg') === false) return 'Google Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Mozilla Firefox';
        if (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) return 'Apple Safari';
        if (strpos($userAgent, 'Edg') !== false) return 'Microsoft Edge';
        if (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) return 'Opera';
        
        return 'Unknown Browser';
    }

    /**
     * Get operating system
     */
    private static function getOperatingSystem()
    {
        $userAgent = request()->userAgent();
        
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'macOS';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iOS') !== false || strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) return 'iOS';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        
        return 'Unknown OS';
    }
}
