<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assisted_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained('users');
            $table->foreignId('assistant_id')->constrained('users');
            $table->foreignId('election_id')->constrained('elections');
            $table->foreignId('candidate_id')->constrained('candidates');
            $table->string('assistant_name', 100);
            $table->string('assistant_relationship', 100)->nullable();
            $table->boolean('voter_consent')->default(true);
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assisted_votes');
    }
};
