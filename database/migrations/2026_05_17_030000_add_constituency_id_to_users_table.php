<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('constituency_id')->nullable()->after('id');
            $table->foreign('constituency_id')
                  ->references('id')
                  ->on('constituencies')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['constituency_id']);
            $table->dropColumn('constituency_id');
        });
    }
};
