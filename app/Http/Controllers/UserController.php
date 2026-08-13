<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        $roles = Role::all();
        return view('users.index', compact('locations', 'roles'));
    }

    public function create()
    {
        return redirect()->route('users.index');
    }

    public function getData(Request $request)
    {
        $users = User::with(['location', 'roles', 'assignedProjects']);
        
        return DataTables::of($users)
            ->addColumn('location_name', function($user) {
                return $user->location ? $user->location->name : '-';
            })
            ->addColumn('role', function($user) {
                return $user->roles->pluck('name')->map(function($role) {
                    $badgeColor = [
                        'admin' => 'danger',
                        'gm' => 'primary',
                        'manager' => 'info',
                        'checker' => 'warning',
                        'storekeeper' => 'success',
                        'site_engineer' => 'secondary',
                        'head_office' => 'dark',
                        'master_data' => 'primary',
                    ];
                    $color = $badgeColor[$role] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucwords(str_replace('_', ' ', $role)) . '</span>';
                })->implode(' ');
            })
            ->addColumn('assigned_projects', function($user) {
                if ($user->hasAnyRole(['admin', 'gm', 'manager', 'checker', 'head_office', 'master_data'])) {
                    return '<span class="badge bg-success">All Projects</span>';
                }
                $projects = $user->assignedProjects->pluck('name')->map(function($name) {
                    return '<span class="badge bg-secondary">' . $name . '</span>';
                })->implode(' ');
                return $projects ?: '<span class="text-muted">No projects assigned</span>';
            })
            ->addColumn('status', function($user) {
                if ($user->is_active) {
                    return '<span class="badge bg-success">Active</span>';
                } else {
                    return '<span class="badge bg-danger">Inactive</span>';
                }
            })
            ->addColumn('actions', function($user) {
                return '
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info btn-sm" onclick="viewUser('.$user->id.')" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="editUser('.$user->id.')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteUser('.$user->id.')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['role', 'assigned_projects', 'status', 'actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'location_id' => $request->location_id,
            'is_active' => $request->is_active ?? true,
        ]);

        $user->assignRole($request->role);

        if ($request->has('assigned_projects') && !in_array($request->role, ['admin', 'gm', 'manager', 'checker', 'head_office', 'master_data'])) {
            $user->assignedProjects()->sync($request->assigned_projects);
        }

        return response()->json(['success' => true, 'message' => 'User created successfully!']);
    }

    public function show($id)
    {
        $user = User::with(['location', 'roles', 'assignedProjects'])->findOrFail($id);
        return response()->json($user);
    }

    public function edit($id)
    {
        $user = User::with(['location', 'roles', 'assignedProjects'])->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location_id' => $request->location_id,
            'is_active' => $request->is_active ?? true,
        ]);

        if ($request->password) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        $user->syncRoles([$request->role]);

        if ($request->has('assigned_projects') && !in_array($request->role, ['admin', 'gm', 'manager', 'checker', 'head_office', 'master_data'])) {
            $user->assignedProjects()->sync($request->assigned_projects);
        } else {
            $user->assignedProjects()->detach();
        }

        return response()->json(['success' => true, 'message' => 'User updated successfully!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->assignedProjects()->detach();
        $user->delete();
        
        return response()->json(['success' => true, 'message' => 'User deleted successfully!']);
    }
}
