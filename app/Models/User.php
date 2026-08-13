<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'location_id', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function assignedProjects()
    {
        return $this->belongsToMany(Location::class, 'user_project_assignments', 'user_id', 'location_id')
            ->withTimestamps();
    }

    public function createdTransactions()
    {
        return $this->hasMany(StockTransaction::class, 'created_by');
    }

    /**
     * Check if user has access to a specific project/location
     */
    public function hasProjectAccess($locationId)
    {
        // Checker, Manager, GM roles have access to all projects
        if ($this->hasAnyRole(['checker', 'manager', 'gm', 'admin'])) {
            return true;
        }
        
        // Other roles only access assigned projects
        return $this->assignedProjects()->where('location_id', $locationId)->exists();
    }

    /**
     * Get accessible project IDs for this user
     */
    public function getAccessibleProjectIds()
    {
        // Checker, Manager, GM roles have access to all projects
        if ($this->hasAnyRole(['checker', 'manager', 'gm', 'admin'])) {
            return Location::where('is_active', true)->pluck('id')->toArray();
        }
        
        // Other roles only access assigned projects
        return $this->assignedProjects()->pluck('location_id')->toArray();
    }

    /**
     * Check if user is a checker, manager, or GM
     */
    public function isHighLevelRole()
    {
        return $this->hasAnyRole(['checker', 'manager', 'gm', 'admin']);
    }
}
