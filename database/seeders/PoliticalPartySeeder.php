<?php

namespace Database\Seeders;

use App\Models\PoliticalParty;
use Illuminate\Database\Seeder;

class PoliticalPartySeeder extends Seeder
{
    public function run(): void
    {
        $parties = [
            ['name' => 'Chama Cha Mapinduzi', 'abbreviation' => 'CCM', 'registration_number' => '001'],
            ['name' => 'Chama cha Demokrasia na Maendeleo', 'abbreviation' => 'CHADEMA', 'registration_number' => '002'],
            ['name' => 'Civic United Front', 'abbreviation' => 'CUF', 'registration_number' => '003'],
            ['name' => 'National Convention for Construction and Reform', 'abbreviation' => 'NCCR-Mageuzi', 'registration_number' => '004'],
            ['name' => 'Tanzania Labour Party', 'abbreviation' => 'TLP', 'registration_number' => '005'],
            ['name' => 'United Democratic Party', 'abbreviation' => 'UDP', 'registration_number' => '006'],
            ['name' => 'Alliance for Change and Transparency', 'abbreviation' => 'ACT-Wazalendo', 'registration_number' => '007'],
            ['name' => 'Alliance for Democratic Change', 'abbreviation' => 'ADC', 'registration_number' => '008'],
            ['name' => 'Chama cha Kijamii', 'abbreviation' => 'CCK', 'registration_number' => '009'],
            ['name' => 'Democratic Party', 'abbreviation' => 'DP', 'registration_number' => '010'],
            ['name' => 'United People\'s Democratic Party', 'abbreviation' => 'UPDP', 'registration_number' => '011'],
            ['name' => 'National League for Democracy', 'abbreviation' => 'NLD', 'registration_number' => '012'],
            ['name' => 'Sauti ya Umma', 'abbreviation' => 'SAU', 'registration_number' => '013'],
            ['name' => 'National Integrity Party', 'abbreviation' => 'NIP', 'registration_number' => '014'],
            ['name' => 'Chama cha Ukweli na Ustawi wa Taifa', 'abbreviation' => 'CHAUSTA', 'registration_number' => '015'],
            ['name' => 'Jahazi Asilia', 'abbreviation' => 'JAHAZI ASILIA', 'registration_number' => '016'],
            ['name' => 'Party of National Awareness', 'abbreviation' => 'PONA', 'registration_number' => '017'],
            ['name' => 'PPT-Maendeleo', 'abbreviation' => 'PPT-Maendeleo', 'registration_number' => '018'],
        ];

        foreach ($parties as $party) {
            PoliticalParty::firstOrCreate(
                ['abbreviation' => $party['abbreviation']],
                $party
            );
        }
    }
}
