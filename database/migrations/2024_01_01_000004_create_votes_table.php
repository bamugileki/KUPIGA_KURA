<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('candidate_id')->constrained('candidates');
            $table->foreignId('election_id')->constrained('elections');
            $table->timestamp('timestamp')->useCurrent();
            $table->unique(['user_id', 'election_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
};
