<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_role')->nullable();
            $table->string('action_type', 50); // CREATE, UPDATE, DELETE, VIEW, LOGIN, LOGOUT, EXPORT
            $table->text('action_description')->nullable();
            $table->string('document_type', 50)->nullable(); // ITEM, TRANSACTION, CATEGORY, LOCATION, USER, REPORT
            $table->unsignedBigInteger('document_id')->nullable();
            $table->string('document_name')->nullable();
            $table->string('module', 50)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('pc_name')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('operating_system')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
            $table->string('location_name')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('action_type');
            $table->index('document_type');
            $table->index('document_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
