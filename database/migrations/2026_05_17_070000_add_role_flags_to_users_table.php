<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_voter')->default(false)->after('role');
            $table->boolean('is_candidate')->default(false)->after('is_voter');
            $table->boolean('is_admin')->default(false)->after('is_candidate');
            $table->boolean('is_officer')->default(false)->after('is_admin');
            $table->boolean('is_observer')->default(false)->after('is_officer');
        });

        DB::statement("UPDATE users SET is_voter = TRUE WHERE role IN ('voter', 'candidate', 'admin', 'electoral_officer', 'polling_agent')");
        DB::statement("UPDATE users SET is_candidate = TRUE WHERE role = 'candidate'");
        DB::statement("UPDATE users SET is_admin = TRUE WHERE role = 'admin'");
        DB::statement("UPDATE users SET is_officer = TRUE WHERE role = 'electoral_officer'");
        DB::statement("UPDATE users SET is_observer = TRUE WHERE role = 'observer'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_voter', 'is_candidate', 'is_admin', 'is_officer', 'is_observer']);
        });
    }
};
