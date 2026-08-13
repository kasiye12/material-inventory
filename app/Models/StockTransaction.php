<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_number', 'transaction_date', 'transaction_type',
        'item_id', 'from_location_id', 'to_location_id', 'quantity',
        'reference_number', 'document_number', 'remarks', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transaction) {
            if (empty($transaction->transaction_number)) {
                $transaction->transaction_number = self::generateTransactionNumber();
            }
        });
    }

    public static function generateTransactionNumber()
    {
        $prefix = 'TRX';
        $timestamp = now()->format('YmdHis');
        $random = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        return $prefix . '-' . $timestamp . '-' . $random;
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
