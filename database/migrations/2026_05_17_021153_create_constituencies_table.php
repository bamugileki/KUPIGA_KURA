<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('constituencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('region', 100);
            $table->string('ward', 100)->nullable();
            $table->timestamps();
        });

        DB::table('constituencies')->insert([
            ['name' => 'Kinondoni', 'region' => 'Dar es Salaam', 'ward' => 'Kinondoni'],
            ['name' => 'Kawe', 'region' => 'Dar es Salaam', 'ward' => 'Kawe'],
            ['name' => 'Ubungo', 'region' => 'Dar es Salaam', 'ward' => 'Ubungo'],
            ['name' => 'Kigamboni', 'region' => 'Dar es Salaam', 'ward' => 'Kigamboni'],
            ['name' => 'Temeke', 'region' => 'Dar es Salaam', 'ward' => 'Temeke'],
            ['name' => 'Ilala', 'region' => 'Dar es Salaam', 'ward' => 'Ilala'],
            ['name' => 'Arusha', 'region' => 'Arusha', 'ward' => 'Arusha Mjini'],
            ['name' => 'Arumeru', 'region' => 'Arusha', 'ward' => null],
            ['name' => 'Mwanza', 'region' => 'Mwanza', 'ward' => 'Mwanza Mjini'],
            ['name' => 'Ilemela', 'region' => 'Mwanza', 'ward' => 'Ilemela'],
            ['name' => 'Mbeya', 'region' => 'Mbeya', 'ward' => 'Mbeya Mjini'],
            ['name' => 'Mbozi', 'region' => 'Mbeya', 'ward' => null],
            ['name' => 'Morogoro', 'region' => 'Morogoro', 'ward' => 'Morogoro Mjini'],
            ['name' => 'Kilosa', 'region' => 'Morogoro', 'ward' => null],
            ['name' => 'Tanga', 'region' => 'Tanga', 'ward' => 'Tanga Mjini'],
            ['name' => 'Korogwe', 'region' => 'Tanga', 'ward' => null],
            ['name' => 'Dodoma', 'region' => 'Dodoma', 'ward' => 'Dodoma Mjini'],
            ['name' => 'Kondoa', 'region' => 'Dodoma', 'ward' => null],
            ['name' => 'Kilimanjaro', 'region' => 'Kilimanjaro', 'ward' => 'Moshi'],
            ['name' => 'Hai', 'region' => 'Kilimanjaro', 'ward' => null],
            ['name' => 'Kagera', 'region' => 'Kagera', 'ward' => 'Bukoba'],
            ['name' => 'Muleba', 'region' => 'Kagera', 'ward' => null],
            ['name' => 'Kigoma', 'region' => 'Kigoma', 'ward' => 'Kigoma Mjini'],
            ['name' => 'Kakonko', 'region' => 'Kigoma', 'ward' => null],
            ['name' => 'Shinyanga', 'region' => 'Shinyanga', 'ward' => 'Shinyanga Mjini'],
            ['name' => 'Kahama', 'region' => 'Shinyanga', 'ward' => null],
            ['name' => 'Tabora', 'region' => 'Tabora', 'ward' => 'Tabora Mjini'],
            ['name' => 'Nzega', 'region' => 'Tabora', 'ward' => null],
            ['name' => 'Ruvuma', 'region' => 'Ruvuma', 'ward' => 'Songea'],
            ['name' => 'Mbinga', 'region' => 'Ruvuma', 'ward' => null],
            ['name' => 'Iringa', 'region' => 'Iringa', 'ward' => 'Iringa Mjini'],
            ['name' => 'Mufindi', 'region' => 'Iringa', 'ward' => null],
            ['name' => 'Pwani', 'region' => 'Pwani', 'ward' => 'Kibaha'],
            ['name' => 'Rufiji', 'region' => 'Pwani', 'ward' => null],
            ['name' => 'Mtwara', 'region' => 'Mtwara', 'ward' => 'Mtwara Mjini'],
            ['name' => 'Masasi', 'region' => 'Mtwara', 'ward' => null],
            ['name' => 'Lindi', 'region' => 'Lindi', 'ward' => 'Lindi Mjini'],
            ['name' => 'Nachingwea', 'region' => 'Lindi', 'ward' => null],
            ['name' => 'Singida', 'region' => 'Singida', 'ward' => 'Singida Mjini'],
            ['name' => 'Manyoni', 'region' => 'Singida', 'ward' => null],
            ['name' => 'Rukwa', 'region' => 'Rukwa', 'ward' => 'Sumbawanga'],
            ['name' => 'Nkasi', 'region' => 'Rukwa', 'ward' => null],
            ['name' => 'Mara', 'region' => 'Mara', 'ward' => 'Musoma'],
            ['name' => 'Butiama', 'region' => 'Mara', 'ward' => null],
            ['name' => 'Njombe', 'region' => 'Njombe', 'ward' => 'Njombe Mjini'],
            ['name' => 'Makete', 'region' => 'Njombe', 'ward' => null],
            ['name' => 'Simiyu', 'region' => 'Simiyu', 'ward' => 'Bariadi'],
            ['name' => 'Busega', 'region' => 'Simiyu', 'ward' => null],
            ['name' => 'Geita', 'region' => 'Geita', 'ward' => 'Geita Mjini'],
            ['name' => 'Chato', 'region' => 'Geita', 'ward' => null],
            ['name' => 'Katavi', 'region' => 'Katavi', 'ward' => 'Mpanda'],
            ['name' => 'Mlele', 'region' => 'Katavi', 'ward' => null],
            ['name' => 'Songwe', 'region' => 'Songwe', 'ward' => 'Vwawa'],
            ['name' => 'Tunduma', 'region' => 'Songwe', 'ward' => null],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('constituencies');
    }
};