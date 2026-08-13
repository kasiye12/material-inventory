<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'action_type',
        'action_description',
        'document_type',
        'document_id',
        'document_name',
        'module',
        'ip_address',
        'pc_name',
        'user_agent',
        'browser',
        'operating_system',
        'old_values',
        'new_values',
        'location_id',
        'location_name',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
