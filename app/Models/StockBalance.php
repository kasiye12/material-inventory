<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'location_id', 'balance_date',
        'opening_balance', 'grv_quantity', 'istrv_quantity',
        'siv_quantity', 'transfer_out_quantity', 'store_return_quantity',
        'closing_balance'
    ];

    protected $casts = [
        'balance_date' => 'date',
        'opening_balance' => 'decimal:2',
        'grv_quantity' => 'decimal:2',
        'istrv_quantity' => 'decimal:2',
        'siv_quantity' => 'decimal:2',
        'transfer_out_quantity' => 'decimal:2',
        'store_return_quantity' => 'decimal:2',
        'closing_balance' => 'decimal:2'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
