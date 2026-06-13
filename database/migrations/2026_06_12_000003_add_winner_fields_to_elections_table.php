<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->boolean('winner_declared')->default(false)->after('voting_enabled');
            $table->foreignId('winner_candidate_id')->nullable()->constrained('candidates')->nullOnDelete()->after('winner_declared');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropForeign(['winner_candidate_id']);
            $table->dropColumn(['winner_declared', 'winner_candidate_id']);
        });
    }
};
