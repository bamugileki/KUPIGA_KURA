<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('accessibility_enabled')->default(false)->after('constituency_id');
            $table->json('disability_type')->nullable()->after('accessibility_enabled');
            $table->string('accessibility_mode', 30)->default('normal')->after('disability_type');
            $table->boolean('high_contrast')->default(false)->after('accessibility_mode');
            $table->string('text_size', 10)->default('medium')->after('high_contrast');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['accessibility_enabled', 'disability_type', 'accessibility_mode', 'high_contrast', 'text_size']);
        });
    }
};
