<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->timestamp('objection_deadline')->nullable()->after('campaign_end');
            $table->boolean('objection_triggered')->default(false)->after('objection_deadline');
        });
    }

    public function down()
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn(['objection_deadline', 'objection_triggered']);
        });
    }
};
