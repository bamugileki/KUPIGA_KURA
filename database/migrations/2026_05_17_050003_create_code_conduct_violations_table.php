<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('code_conduct_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reported_by')->constrained('users');
            $table->foreignId('accused_user_id')->nullable()->constrained('users');
            $table->foreignId('candidate_id')->nullable()->constrained('candidates');
            $table->text('description');
            $table->text('evidence')->nullable();
            $table->string('status', 30)->default('pending'); // pending, investigated, substantiated, dismissed
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('code_conduct_violations');
    }
};
