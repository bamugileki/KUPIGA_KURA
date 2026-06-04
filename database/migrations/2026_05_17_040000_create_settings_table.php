<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Default settings
        DB::table('settings')->insert([
            ['key' => 'max_failed_attempts', 'value' => '3'],
            ['key' => 'lock_minutes_base', 'value' => '30'],
            ['key' => 'lock_multiplier', 'value' => '2'],
            ['key' => 'default_language', 'value' => 'en'],
            ['key' => 'session_timeout', 'value' => '120'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
