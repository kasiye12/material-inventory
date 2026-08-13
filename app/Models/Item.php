<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'unit',
        'unit_price',
        'min_stock_level',
        'max_stock_level',
        'is_active'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'min_stock_level' => 'decimal:2',
        'max_stock_level' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function getCurrentStock($locationId = null)
    {
        $query = StockTransaction::where('item_id', $this->id);
        
        if ($locationId) {
            $received = (clone $query)
                ->where('to_location_id', $locationId)
                ->whereIn('transaction_type', ['GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE'])
                ->sum('quantity');
                
            $issued = (clone $query)
                ->where('from_location_id', $locationId)
                ->whereIn('transaction_type', ['SIV', 'TRANSFER_OUT'])
                ->sum('quantity');
                
            return max(0, round($received - $issued, 2));
        }
        
        $received = (clone $query)
            ->whereIn('transaction_type', ['GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE'])
            ->sum('quantity');
            
        $issued = (clone $query)
            ->whereIn('transaction_type', ['SIV', 'TRANSFER_OUT'])
            ->sum('quantity');
            
        return max(0, round($received - $issued, 2));
    }
}
