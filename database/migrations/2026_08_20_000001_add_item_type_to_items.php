<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'item_type')) {
                $table->enum('item_type', ['regular', 'fixed_asset', 'used_material', 'fuel'])
                    ->default('regular')
                    ->after('unit')
                    ->comment('Item classification: regular, fixed_asset, used_material, fuel');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('item_type');
        });
    }
};
