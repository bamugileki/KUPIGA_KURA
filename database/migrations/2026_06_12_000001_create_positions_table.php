<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name_en', 100);
            $table->string('name_sw', 100);
            $table->text('description')->nullable();
            $table->integer('min_age')->default(18);
            $table->boolean('requires_constituency')->default(false);
            $table->boolean('requires_running_mate')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        $positions = [
            ['slug' => 'presidential', 'name_en' => 'Presidential', 'name_sw' => 'Urais', 'min_age' => 40, 'requires_running_mate' => true, 'sort_order' => 1],
            ['slug' => 'parliamentary', 'name_en' => 'Parliamentary', 'name_sw' => 'Ubunge', 'min_age' => 21, 'requires_constituency' => true, 'sort_order' => 2],
            ['slug' => 'councillor', 'name_en' => 'Councillor', 'name_sw' => 'Udiwani', 'min_age' => 21, 'requires_constituency' => true, 'sort_order' => 3],
            ['slug' => 'president', 'name_en' => 'President', 'name_sw' => 'Rais', 'min_age' => 35, 'sort_order' => 4],
            ['slug' => 'vice_president', 'name_en' => 'Vice President', 'name_sw' => 'Makamu wa Rais', 'min_age' => 35, 'sort_order' => 5],
            ['slug' => 'secretary', 'name_en' => 'Secretary', 'name_sw' => 'Katibu', 'min_age' => 21, 'sort_order' => 6],
            ['slug' => 'treasurer', 'name_en' => 'Treasurer', 'name_sw' => 'Mweka Hazina', 'min_age' => 21, 'sort_order' => 7],
            ['slug' => 'member', 'name_en' => 'Member', 'name_sw' => 'Mjumbe', 'min_age' => 18, 'sort_order' => 8],
        ];

        foreach ($positions as $pos) {
            DB::table('positions')->insert($pos + ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
