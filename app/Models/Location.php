<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'type', 'address', 'contact_person', 'contact_phone', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function fromTransactions()
    {
        return $this->hasMany(StockTransaction::class, 'from_location_id');
    }

    public function toTransactions()
    {
        return $this->hasMany(StockTransaction::class, 'to_location_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
