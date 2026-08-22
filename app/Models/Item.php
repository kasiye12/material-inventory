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
        'item_type', 'unit_price', 'min_stock_level', 'max_stock_level', 'is_active'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'min_stock_level' => 'decimal:2',
        'max_stock_level' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Item type constants
    const TYPE_REGULAR = 'regular';
    const TYPE_FIXED_ASSET = 'fixed_asset';
    const TYPE_USED_MATERIAL = 'used_material';
    const TYPE_FUEL = 'fuel';

    // IN transaction types (add to stock)
    public static $inTypes = [
        'GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE',
        'SRV', 'TTRV', 'FARV', 'UMTRV', 'FGRV', 'FRV'
    ];

    // OUT transaction types (subtract from stock)
    public static $outTypes = [
        'SIV', 'TRANSFER_OUT', 'FIV', 'UMIV', 'UMTV'
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

    public function getItemTypeLabel()
    {
        $labels = [
            self::TYPE_REGULAR => 'Regular Material',
            self::TYPE_FIXED_ASSET => 'Fixed Asset',
            self::TYPE_USED_MATERIAL => 'Used Material',
            self::TYPE_FUEL => 'Fuel',
        ];
        
        return $labels[$this->item_type] ?? 'Regular Material';
    }

    public function getItemTypeBadge()
    {
        $colors = [
            self::TYPE_REGULAR => 'success',
            self::TYPE_FIXED_ASSET => 'primary',
            self::TYPE_USED_MATERIAL => 'warning',
            self::TYPE_FUEL => 'info',
        ];
        
        $color = $colors[$this->item_type] ?? 'secondary';
        $label = $this->getItemTypeLabel();
        
        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
    }
}
