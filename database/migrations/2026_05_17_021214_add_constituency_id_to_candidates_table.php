<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('residential_address', 255)->nullable()->after('email');
            $table->string('party_membership_number', 50)->nullable()->after('party_registration_number');
            $table->string('ward_name', 100)->nullable()->after('constituency');
            $table->foreignId('constituency_id')->nullable()->constrained('constituencies')->after('ward_name');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['constituency_id']);
            $table->dropColumn(['constituency_id', 'residential_address', 'party_membership_number', 'ward_name']);
        });
    }
};
