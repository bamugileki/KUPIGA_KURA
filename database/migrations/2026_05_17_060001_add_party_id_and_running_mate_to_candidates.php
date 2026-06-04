<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->foreignId('party_id')->nullable()->after('position')
                ->constrained('political_parties');
            $table->string('running_mate_name', 100)->nullable()->after('party_membership_number');
            $table->string('running_mate_photo', 255)->nullable()->after('running_mate_name');
            $table->timestamp('nomination_submitted_at')->nullable()->after('approved_at');
        });
    }

    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['party_id']);
            $table->dropColumn(['party_id', 'running_mate_name', 'running_mate_photo', 'nomination_submitted_at']);
        });
    }
};
