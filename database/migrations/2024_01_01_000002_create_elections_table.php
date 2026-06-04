<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title_en', 200);
            $table->string('title_sw', 200);
            $table->string('election_type', 30);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->string('status', 30)->default('draft');
            $table->boolean('candidates_published')->default(false);
            $table->boolean('voting_enabled')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('elections');
    }
};
