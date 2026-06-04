<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->timestamp('nomination_start')->nullable()->after('end_time');
            $table->timestamp('nomination_end')->nullable()->after('nomination_start');
            $table->timestamp('campaign_start')->nullable()->after('nomination_end');
            $table->timestamp('campaign_end')->nullable()->after('campaign_start');
        });
    }

    public function down()
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn(['nomination_start', 'nomination_end', 'campaign_start', 'campaign_end']);
        });
    }
};
