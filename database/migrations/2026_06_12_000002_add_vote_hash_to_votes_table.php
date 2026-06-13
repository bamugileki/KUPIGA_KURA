<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->string('vote_hash', 64)->unique()->nullable()->after('id');
        });

        // Populate vote_hash for existing votes
        $votes = DB::table('votes')->get();
        foreach ($votes as $vote) {
            $hash = hash('sha256', $vote->user_id . '_' . $vote->election_id . '_' . config('app.key'));
            DB::table('votes')->where('id', $vote->id)->update(['vote_hash' => $hash]);
        }
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn('vote_hash');
        });
    }
};
