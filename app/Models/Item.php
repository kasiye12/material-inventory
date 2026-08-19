<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'description', 'category_id', 'unit',
        'unit_price', 'min_stock_level', 'max_stock_level', 'is_active'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'min_stock_level' => 'decimal:2',
        'max_stock_level' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // IN transaction types (add to stock)
    public static $inTypes = [
        'GRV',              // Goods Received Voucher
        'ISTRV',            // Inter Store Transfer Receiving Voucher
        'STORE_RETURN',     // Store Return
        'BEGINNING_BALANCE', // Opening Stock
        'SRV',              // Store Return Voucher
        'TTRV',             // Temporary Transfer Receiving Voucher
        'FARV',             // Fixed Asset Receiving Voucher
        'UMTRV',            // Used Material Transfer Receiving Voucher
        'FGRV',             // Finished Good Receiving Voucher
        'FRV',              // Fuel Receiving Voucher
    ];

    // OUT transaction types (subtract from stock)
    public static $outTypes = [
        'SIV',              // Store Issue Voucher
        'TRANSFER_OUT',     // Transfer Out
        'FIV',              // Fuel Issue Voucher
        'UMIV',             // Used Material Issue Voucher
        'UMTV',             // Used Material Transfer Voucher
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
                ->whereIn('transaction_type', self::$inTypes)
                ->sum('quantity');
                
            $issued = (clone $query)
                ->where('from_location_id', $locationId)
                ->whereIn('transaction_type', self::$outTypes)
                ->sum('quantity');
                
            return max(0, round($received - $issued, 2));
        }
        
        $received = (clone $query)
            ->whereIn('transaction_type', self::$inTypes)
            ->sum('quantity');
            
        $issued = (clone $query)
            ->whereIn('transaction_type', self::$outTypes)
            ->sum('quantity');
            
        return max(0, round($received - $issued, 2));
    }
}
