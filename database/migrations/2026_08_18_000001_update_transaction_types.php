<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify transaction_type enum to include all voucher types
        DB::statement("ALTER TABLE stock_transactions MODIFY COLUMN transaction_type ENUM(
            'GRV',           -- Goods Received Voucher
            'ISTRV',         -- Inter Store Transfer Received Voucher
            'SIV',           -- Store Issue Voucher
            'TRANSFER_OUT',  -- Transfer Out
            'STORE_RETURN',  -- Store Return
            'BEGINNING_BALANCE', -- Beginning Balance
            'SRV',           -- Store Return Voucher
            'FIV',           -- Fuel Issue Voucher
            'UMIV',          -- Used Material Issue Voucher
            'TTRV',          -- Temporary Transfer Receiving Voucher
            'FARV',          -- Fixed Asset Receiving Voucher
            'UMTV',          -- Used Material Transfer Voucher
            'UMTRV',         -- Used Material Transfer Receiving Voucher
            'FGRV',          -- Finished Good Receiving Voucher
            'FRV'            -- Fuel Receiving Voucher
        )");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE stock_transactions MODIFY COLUMN transaction_type ENUM(
            'GRV', 'ISTRV', 'SIV', 'TRANSFER_OUT', 'STORE_RETURN', 'BEGINNING_BALANCE'
        )");
    }
};
