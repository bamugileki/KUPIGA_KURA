<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users');
            $table->foreignId('election_id')->nullable()->constrained('elections');
            $table->string('position', 30);
            $table->string('constituency', 100)->nullable();
            $table->text('manifesto')->nullable();
            $table->string('status', 20)->default('pending');
            $table->boolean('terms_accepted')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidates');
    }
};
