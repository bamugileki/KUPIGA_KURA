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
            $table->string('full_name')->nullable()->after('user_id');
            $table->string('gender', 10)->nullable()->after('full_name');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('nationality', 50)->default('Tanzanian')->after('date_of_birth');
            $table->string('phone', 20)->nullable()->after('nationality');
            $table->string('email', 120)->nullable()->after('phone');
            $table->string('nida_number', 25)->unique()->nullable()->after('email');
            $table->string('party_name', 100)->nullable()->after('position');
            $table->string('party_abbreviation', 20)->nullable()->after('party_name');
            $table->string('party_leader', 100)->nullable()->after('party_abbreviation');
            $table->string('party_registration_number', 50)->nullable()->after('party_leader');
            $table->string('photo', 255)->nullable()->after('party_registration_number');
            $table->string('party_logo', 255)->nullable()->after('photo');
            $table->json('documents')->nullable()->after('party_logo');
            $table->text('biography')->nullable()->after('documents');
            $table->text('education')->nullable()->after('biography');
            $table->text('political_experience')->nullable()->after('education');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'full_name', 'gender', 'date_of_birth', 'nationality', 'phone', 'email', 'nida_number',
                'party_name', 'party_abbreviation', 'party_leader', 'party_registration_number',
                'photo', 'party_logo', 'documents', 'biography', 'education', 'political_experience',
            ]);
        });
    }
};
