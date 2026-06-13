<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        $schema = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='votes'");

        if ($schema) {
            $tempTable = 'votes_temp_' . uniqid();

            Schema::create($tempTable, function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users');
                $table->text('candidate_id');
                $table->string('vote_hash', 64)->unique()->nullable();
                $table->foreignId('election_id')->constrained('elections');
                $table->timestamp('timestamp')->useCurrent();
            });

            $existingColumns = DB::select("PRAGMA table_info('votes')");
            $columnNames = array_map(fn($col) => $col->name, $existingColumns);
            $commonColumns = array_intersect($columnNames, ['id', 'user_id', 'candidate_id', 'vote_hash', 'election_id', 'timestamp']);
            if (!empty($commonColumns)) {
                $cols = implode(', ', $commonColumns);
                DB::statement("INSERT INTO {$tempTable} ({$cols}) SELECT {$cols} FROM votes");
            }

            Schema::drop('votes');
            Schema::rename($tempTable, 'votes');
        } else {
            Schema::create('votes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users');
                $table->text('candidate_id');
                $table->string('vote_hash', 64)->unique()->nullable();
                $table->foreignId('election_id')->constrained('elections');
                $table->timestamp('timestamp')->useCurrent();
            });
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::drop('votes');

        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('candidate_id')->constrained('candidates');
            $table->foreignId('election_id')->constrained('elections');
            $table->timestamp('timestamp')->useCurrent();
        });

        $hashAdded = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='votes'");
        if ($hashAdded) {
            Schema::table('votes', function (Blueprint $table) {
                $table->string('vote_hash', 64)->unique()->nullable()->after('id');
            });
        }

        Schema::enableForeignKeyConstraints();
    }
};
