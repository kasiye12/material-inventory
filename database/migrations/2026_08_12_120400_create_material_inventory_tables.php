<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('categories')->insert([
            ['name' => 'Cement', 'code' => 'CEM', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Concrete', 'code' => 'CON', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Re-Bar', 'code' => 'RBR', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sand', 'code' => 'SND', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aggregate', 'code' => 'AGG', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chemicals', 'code' => 'CHM', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Steel', 'code' => 'STL', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wood', 'code' => 'WOD', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Plumbing', 'code' => 'PLB', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Electrical', 'code' => 'ELC', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Locations
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->enum('type', ['head_office', 'project', 'site', 'store']);
            $table->text('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('locations')->insert([
            ['name' => 'Head Office', 'code' => 'HO', 'type' => 'head_office', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nefas Silk', 'code' => 'NS', 'type' => 'site', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'EAU South Campus Project', 'code' => 'EAU-SC', 'type' => 'project', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Main Store', 'code' => 'MS', 'type' => 'store', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('unit', 20);
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->decimal('min_stock_level', 10, 2)->default(0.00);
            $table->decimal('max_stock_level', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Add fields to users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'location_id')) {
                $table->foreignId('location_id')->nullable()->after('phone')->constrained('locations')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('location_id');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // Stock Transactions
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number', 50)->unique();
            $table->date('transaction_date');
            $table->enum('transaction_type', ['GRV', 'ISTRV', 'SIV', 'TRANSFER_OUT', 'STORE_RETURN', 'BEGINNING_BALANCE']);
            $table->foreignId('item_id')->constrained()->onDelete('restrict');
            $table->foreignId('from_location_id')->nullable()->constrained('locations')->onDelete('restrict');
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->onDelete('restrict');
            $table->decimal('quantity', 12, 2);
            $table->string('reference_number', 100)->nullable();
            $table->string('document_number', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Stock Balances
        Schema::create('stock_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->date('balance_date');
            $table->decimal('opening_balance', 12, 2)->default(0.00);
            $table->decimal('grv_quantity', 12, 2)->default(0.00);
            $table->decimal('istrv_quantity', 12, 2)->default(0.00);
            $table->decimal('siv_quantity', 12, 2)->default(0.00);
            $table->decimal('transfer_out_quantity', 12, 2)->default(0.00);
            $table->decimal('store_return_quantity', 12, 2)->default(0.00);
            $table->decimal('closing_balance', 12, 2)->default(0.00);
            $table->timestamps();
            $table->unique(['item_id', 'location_id', 'balance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_balances');
        Schema::dropIfExists('stock_transactions');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn(['phone', 'location_id', 'is_active', 'deleted_at']);
        });
        Schema::dropIfExists('items');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('categories');
    }
};
