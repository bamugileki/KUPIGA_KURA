<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('objections', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30); // nomination, petition
            $table->foreignId('objector_id')->constrained('users');
            $table->foreignId('candidate_id')->nullable()->constrained('candidates');
            $table->foreignId('election_id')->nullable()->constrained('elections');
            $table->text('reason');
            $table->text('evidence')->nullable();
            $table->string('status', 30)->default('pending'); // pending, upheld, dismissed
            $table->text('admin_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('objections');
    }
};
