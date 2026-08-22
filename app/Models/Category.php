<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            // Auto-generate code if not provided
            if (empty($category->code)) {
                $category->code = self::generateUniqueCode($category->name);
            }
        });
    }

    public static function generateUniqueCode($name)
    {
        $clean = preg_replace('/[^a-zA-Z0-9]/', '', $name);
        $code = strtoupper(substr($clean, 0, 3));
        
        if (empty($code)) {
            $code = 'CAT';
        }
        
        $originalCode = $code;
        $counter = 1;
        
        while (self::where('code', $code)->exists()) {
            $code = $originalCode . $counter;
            $counter++;
        }
        
        return $code;
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
