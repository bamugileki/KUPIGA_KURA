<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('political_parties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('abbreviation', 20)->unique();
            $table->string('logo', 255)->nullable();
            $table->string('registration_number', 50)->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('political_parties');
    }
};
