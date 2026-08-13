<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'password_hint')) {
                $table->string('password_hint', 100)->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'security_question')) {
                $table->string('security_question', 200)->nullable()->after('password_hint');
            }
            if (!Schema::hasColumn('users', 'security_answer')) {
                $table->string('security_answer', 200)->nullable()->after('security_question');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['password_hint', 'security_question', 'security_answer']);
        });
    }
};
