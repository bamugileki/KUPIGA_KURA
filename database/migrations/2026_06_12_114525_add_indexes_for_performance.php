<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index(['status', 'is_verified']);
            $table->index('created_at');
        });

        Schema::table('elections', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->index('status');
            $table->index('election_id');
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->index('election_id');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['action', 'timestamp']);
        });

        Schema::table('suspicious_logs', function (Blueprint $table) {
            $table->index('timestamp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status', 'is_verified']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('elections', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['election_id']);
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex(['election_id']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['action', 'timestamp']);
        });

        Schema::table('suspicious_logs', function (Blueprint $table) {
            $table->dropIndex(['timestamp']);
        });
    }
};
