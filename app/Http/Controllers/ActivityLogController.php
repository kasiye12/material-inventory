<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('activity-logs.index');
    }

    public function getData(Request $request)
    {
        $logs = ActivityLog::query();
        
        if ($request->action_type) {
            $logs->where('action_type', $request->action_type);
        }
        if ($request->document_type) {
            $logs->where('document_type', $request->document_type);
        }
        if ($request->user_id) {
            $logs->where('user_id', $request->user_id);
        }
        if ($request->date_from) {
            $logs->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $logs->whereDate('created_at', '<=', $request->date_to);
        }

        return DataTables::of($logs)
            ->addColumn('action_badge', function($log) {
                $colors = [
                    'CREATE' => 'success',
                    'UPDATE' => 'warning',
                    'DELETE' => 'danger',
                    'VIEW' => 'info',
                    'LOGIN' => 'primary',
                    'LOGOUT' => 'secondary',
                    'EXPORT' => 'dark',
                ];
                $color = $colors[$log->action_type] ?? 'secondary';
                return '<span class="badge bg-' . $color . '">' . $log->action_type . '</span>';
            })
            ->addColumn('document_info', function($log) {
                if (!$log->document_type) return '-';
                return '<strong>' . $log->document_type . '</strong><br><small>' . $log->document_name . '</small>';
            })
            ->addColumn('pc_info', function($log) {
                return $log->pc_name . '<br><small class="text-muted">' . $log->browser . ' | ' . $log->operating_system . '</small>';
            })
            ->rawColumns(['action_badge', 'document_info', 'pc_info'])
            ->make(true);
    }

    public function show($id)
    {
        return response()->json(ActivityLog::findOrFail($id));
    }
}
