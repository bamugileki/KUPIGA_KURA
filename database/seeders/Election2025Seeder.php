<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\PoliticalParty;
use App\Models\Constituency;
use App\Models\Position;
use App\Models\AuditLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Election2025Seeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'full_name' => 'Tume Huru ya Taifa ya Uchaguzi Tanzania - Admin',
                'email' => 'admin@tume.go.tz',
                'phone' => '+255712345678',
                'nida_number' => '19900101-00001-00001-00',
                'password' => 'Admin@123',
                'role' => 'admin',
                'status' => 'active',
                'language' => 'en',
                'age' => 36,
                'is_verified' => true,
                'email_verified' => true,
            ]);
        }

        $this->command->info('Fetching political parties...');
        $parties = [];
        $partyAbbrs = ['CCM', 'Chaumma', 'CUF', 'NRA', 'Makini', 'AAFP', 'DP', 'NCCR-Mageuzi', 'UDP', 'NLD', 'TLP', 'ADAP', 'UMD', 'SAU', 'ADC', 'CCK', 'UPDP'];
        foreach ($partyAbbrs as $abbr) {
            $party = PoliticalParty::where('abbreviation', $abbr)->first();
            if ($party) {
                $parties[$abbr] = $party->id;
            }
        }

        $constituencies = Constituency::pluck('id', 'name')->toArray();

        $positions = Position::pluck('id', 'slug')->toArray();

        $this->command->info('Creating elections...');

        $presidentialElection = Election::updateOrCreate(
            ['election_type' => 'presidential', 'title_en' => '2025 Tanzanian General Election - Presidential'],
            [
                'title_sw' => 'Uchaguzi Mkuu wa Tanzania 2025 - Urais',
                'start_time' => Carbon::parse('2025-10-29 07:00:00'),
                'end_time' => Carbon::parse('2025-10-29 16:00:00'),
                'nomination_start' => Carbon::parse('2025-08-01 00:00:00'),
                'nomination_end' => Carbon::parse('2025-08-21 00:00:00'),
                'campaign_start' => Carbon::parse('2025-08-22 00:00:00'),
                'campaign_end' => Carbon::parse('2025-10-28 23:59:00'),
                'status' => 'closed',
                'candidates_published' => true,
                'voting_enabled' => true,
                'winner_declared' => true,
                'created_by' => $admin->id,
            ]
        );

        $parliamentaryElection = Election::updateOrCreate(
            ['election_type' => 'parliamentary', 'title_en' => '2025 Tanzanian General Election - Parliamentary'],
            [
                'title_sw' => 'Uchaguzi Mkuu wa Tanzania 2025 - Ubunge',
                'start_time' => Carbon::parse('2025-10-29 07:00:00'),
                'end_time' => Carbon::parse('2025-10-29 16:00:00'),
                'nomination_start' => Carbon::parse('2025-08-01 00:00:00'),
                'nomination_end' => Carbon::parse('2025-08-21 00:00:00'),
                'campaign_start' => Carbon::parse('2025-08-22 00:00:00'),
                'campaign_end' => Carbon::parse('2025-10-28 23:59:00'),
                'status' => 'closed',
                'candidates_published' => true,
                'voting_enabled' => true,
                'winner_declared' => false,
                'created_by' => $admin->id,
            ]
        );

        $this->command->info('Creating presidential candidates...');

        $presidentialCandidates = [
            [
                'name' => 'Samia Suluhu Hassan',
                'party' => 'CCM',
                'running_mate' => 'Dr. Philip Mpango',
                'nida' => '19600127-12345-67890-01',
                'gender' => 'female',
                'dob' => '1960-01-27',
            ],
            [
                'name' => 'Mwalim Salum Juma',
                'party' => 'Chaumma',
                'running_mate' => 'Salum Abdallah Salum',
                'nida' => '19750315-54321-09876-02',
                'gender' => 'male',
                'dob' => '1975-03-15',
            ],
            [
                'name' => 'Gombo Samandito Gombo',
                'party' => 'CUF',
                'running_mate' => 'Salim Mwinyi Salim',
                'nida' => '19681122-11111-22222-03',
                'gender' => 'male',
                'dob' => '1968-11-22',
            ],
            [
                'name' => 'Almas Hassan Kisabya',
                'party' => 'NRA',
                'running_mate' => 'Juma Hassan Mussa',
                'nida' => '19720708-33333-44444-04',
                'gender' => 'male',
                'dob' => '1972-07-08',
            ],
            [
                'name' => 'Coaster Jimmy Kibonde',
                'party' => 'Makini',
                'running_mate' => 'Mwanaisha Abdallah',
                'nida' => '19800512-55555-66666-05',
                'gender' => 'male',
                'dob' => '1980-05-12',
            ],
            [
                'name' => 'Kunje Ngombale Mwiru',
                'party' => 'AAFP',
                'running_mate' => 'Hussein Mwinyi Khamis',
                'nida' => '19740903-77777-88888-06',
                'gender' => 'male',
                'dob' => '1974-09-03',
            ],
            [
                'name' => 'Abdul Juma Mluya',
                'party' => 'DP',
                'running_mate' => 'Fatuma Salim Juma',
                'nida' => '19631219-99999-00000-07',
                'gender' => 'male',
                'dob' => '1963-12-19',
            ],
            [
                'name' => 'Ambar Khamis Haji',
                'party' => 'NCCR-Mageuzi',
                'running_mate' => 'Said Ambar Khamis',
                'nida' => '19710625-12121-34343-08',
                'gender' => 'male',
                'dob' => '1971-06-25',
            ],
            [
                'name' => 'Saum Hussein Rashid',
                'party' => 'UDP',
                'running_mate' => 'Zainab Hussein Rashid',
                'nida' => '19690414-56565-78787-09',
                'gender' => 'male',
                'dob' => '1969-04-14',
            ],
            [
                'name' => 'Doyo Hassan Doyo',
                'party' => 'NLD',
                'running_mate' => 'Asha Doyo Hassan',
                'nida' => '19770830-90909-01010-10',
                'gender' => 'male',
                'dob' => '1977-08-30',
            ],
            [
                'name' => 'Rwamugira Mbatina Yustas',
                'party' => 'TLP',
                'running_mate' => 'Mbatina Yustas Rwamugira',
                'nida' => '19821105-11121-31415-11',
                'gender' => 'male',
                'dob' => '1982-11-05',
            ],
            [
                'name' => 'Bussungu Georges Gabriel',
                'party' => 'ADAP',
                'running_mate' => 'Gabriel Bussungu Georges',
                'nida' => '19751017-16171-81920-12',
                'gender' => 'male',
                'dob' => '1975-10-17',
            ],
            [
                'name' => 'Noty Mwajuma Mirambo',
                'party' => 'UMD',
                'running_mate' => 'Juma Mirambo Noty',
                'nida' => '19830422-21222-32425-13',
                'gender' => 'female',
                'dob' => '1983-04-22',
            ],
            [
                'name' => 'Kyara Majalio Paul',
                'party' => 'SAU',
                'running_mate' => 'Paul Kyara Majalio',
                'nida' => '19701208-26272-82930-14',
                'gender' => 'male',
                'dob' => '1970-12-08',
            ],
            [
                'name' => 'Wilson Elias Mulumbe',
                'party' => 'ADC',
                'running_mate' => 'Elias Mulumbe Wilson',
                'nida' => '19670711-31323-33435-15',
                'gender' => 'male',
                'dob' => '1967-07-11',
            ],
            [
                'name' => 'Mwaijojele David Daud',
                'party' => 'CCK',
                'running_mate' => 'David Daud Mwaijojele',
                'nida' => '19790214-36373-83940-16',
                'gender' => 'male',
                'dob' => '1979-02-14',
            ],
            [
                'name' => 'Twalib Ibrahim Kadege',
                'party' => 'UPDP',
                'running_mate' => 'Ibrahim Kadege Twalib',
                'nida' => '19741005-41424-34445-17',
                'gender' => 'male',
                'dob' => '1974-10-05',
            ],
        ];

        $presidentCandidateIds = [];
        foreach ($presidentialCandidates as $i => $data) {
            $email = 'presidential.candidate.' . ($i + 1) . '@tume.go.tz';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'full_name' => $data['name'],
                    'phone' => '+2557' . str_pad((600000000 + $i), 9, '0', STR_PAD_LEFT),
                    'nida_number' => $data['nida'],
                    'password' => 'Candidate@2025',
                    'role' => 'candidate',
                    'is_candidate' => true,
                    'is_voter' => true,
                    'status' => 'active',
                    'age' => Carbon::parse($data['dob'])->age,
                    'is_verified' => true,
                    'email_verified' => true,
                    'language' => 'sw',
                ]
            );

            $partyId = $parties[$data['party']] ?? null;
            $partyName = $partyId ? (PoliticalParty::find($partyId)->name ?? $data['party']) : $data['party'];

            $candidate = Candidate::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'election_id' => $presidentialElection->id,
                    'position' => 'presidential',
                    'full_name' => $data['name'],
                    'gender' => $data['gender'],
                    'date_of_birth' => $data['dob'],
                    'nationality' => 'Tanzanian',
                    'phone' => $user->phone,
                    'email' => $email,
                    'nida_number' => $data['nida'],
                    'party_id' => $partyId,
                    'party_name' => $partyName,
                    'party_abbreviation' => $data['party'],
                    'running_mate_name' => $data['running_mate'] ?? null,
                    'status' => 'approved',
                    'terms_accepted' => true,
                    'approved_at' => Carbon::parse('2025-09-01 00:00:00'),
                    'nomination_submitted_at' => Carbon::parse('2025-08-15 00:00:00'),
                ]
            );

            $presidentCandidateIds[] = $candidate->id;
        }

        $this->command->info('Declaring presidential winner...');
        $samiaCandidate = Candidate::whereHas('user', function ($q) {
            $q->where('email', 'presidential.candidate.1@tume.go.tz');
        })->first();

        if ($samiaCandidate) {
            $presidentialElection->update([
                'winner_declared' => true,
                'winner_candidate_id' => $samiaCandidate->id,
            ]);
        }

        $this->command->info('Creating parliamentary candidates with multiple parties per constituency...');

        $parliamentaryConstituencies = [
            ['name'=>'Ilala','region'=>'Dar es Salaam','candidates'=>[
                ['name'=>'Mbarouk Ali Mbarouk','party'=>'CCM','nida'=>'19750320-11111-11111-50','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Hassan Abdallah Mwinyi','party'=>'CHADEMA','nida'=>'19810612-22222-22222-51','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Salima Juma Khamis','party'=>'CUF','nida'=>'19781105-33333-33333-52','gender'=>'female','dob'=>'1978-11-05'],
                ['name'=>'Juma Ndefo Mwema','party'=>'ACT-Wazalendo','nida'=>'19700118-44444-44444-53','gender'=>'male','dob'=>'1970-01-18'],
            ],'vote_distribution'=>[320,35,15,10]],
            ['name'=>'Ubungo','region'=>'Dar es Salaam','candidates'=>[
                ['name'=>'Saada Mkuya Salum','party'=>'CCM','nida'=>'19750320-11111-11111-54','gender'=>'female','dob'=>'1975-03-20'],
                ['name'=>'Jerome Bomani Daudi','party'=>'CHADEMA','nida'=>'19810612-22222-22222-55','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Fatma Hassan Mwita','party'=>'NCCR-Mageuzi','nida'=>'19781105-33333-33333-56','gender'=>'female','dob'=>'1978-11-05'],
                ['name'=>'Issa Mwinyi Hamadi','party'=>'CUF','nida'=>'19700118-44444-44444-57','gender'=>'male','dob'=>'1970-01-18'],
            ],'vote_distribution'=>[315,38,17,10]],
            ['name'=>'Kinondoni','region'=>'Dar es Salaam','candidates'=>[
                ['name'=>'Vicent Mgaya Shosi','party'=>'CCM','nida'=>'19750320-11111-11111-58','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Godbless Lema Jonathan','party'=>'CHADEMA','nida'=>'19810612-22222-22222-59','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Amina Mwinyi Sheha','party'=>'CUF','nida'=>'19781105-33333-33333-60','gender'=>'female','dob'=>'1978-11-05'],
                ['name'=>'Daudi Mnyonge Shenga','party'=>'TLP','nida'=>'19700118-44444-44444-61','gender'=>'male','dob'=>'1970-01-18'],
            ],'vote_distribution'=>[305,42,18,15]],
            ['name'=>'Temeke','region'=>'Dar es Salaam','candidates'=>[
                ['name'=>'Abdulaziz Abubakar Gunda','party'=>'CCM','nida'=>'19750320-11111-11111-62','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Zainab Mohamed Mwinyi','party'=>'CHADEMA','nida'=>'19810612-22222-22222-63','gender'=>'female','dob'=>'1981-06-12'],
                ['name'=>'Said Juma Bakari','party'=>'CUF','nida'=>'19781105-33333-33333-64','gender'=>'male','dob'=>'1978-11-05'],
                ['name'=>'Elias Mwantumu Mwita','party'=>'DP','nida'=>'19700118-44444-44444-65','gender'=>'male','dob'=>'1970-01-18'],
            ],'vote_distribution'=>[320,35,15,10]],
            ['name'=>'Kigamboni','region'=>'Dar es Salaam','candidates'=>[
                ['name'=>'Mwantumu Juma Bakari','party'=>'CCM','nida'=>'19750320-11111-11111-66','gender'=>'female','dob'=>'1975-03-20'],
                ['name'=>'Abdallah Said Mbwana','party'=>'CHADEMA','nida'=>'19810612-22222-22222-67','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Mwajuma Hassan Chande','party'=>'CUF','nida'=>'19781105-33333-33333-68','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[230,28,22]],
            ['name'=>'Nyamagana','region'=>'Mwanza','candidates'=>[
                ['name'=>'John Mwita Nzilanyingi','party'=>'CCM','nida'=>'19750320-11111-11111-69','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Joseph Warioba Mwita','party'=>'CHADEMA','nida'=>'19810612-22222-22222-70','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Maria Magessa Ngusa','party'=>'ACT-Wazalendo','nida'=>'19781105-33333-33333-71','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[135,22,13]],
            ['name'=>'Ilemela','region'=>'Mwanza','candidates'=>[
                ['name'=>'Kafiti Kagasheki William','party'=>'CCM','nida'=>'19750320-11111-11111-72','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Simon Wapinda Mwita','party'=>'CUF','nida'=>'19810612-22222-22222-73','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Neema Mahona Nyoni','party'=>'CHADEMA','nida'=>'19781105-33333-33333-74','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[135,20,15]],
            ['name'=>'Arusha','region'=>'Arusha','candidates'=>[
                ['name'=>'Abdallah Salim Manyanda','party'=>'CCM','nida'=>'19750320-11111-11111-75','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Catherine Moshia Lekitare','party'=>'CHADEMA','nida'=>'19810612-22222-22222-76','gender'=>'female','dob'=>'1981-06-12'],
                ['name'=>'Patrick Mollel Ole','party'=>'NCCR-Mageuzi','nida'=>'19781105-33333-33333-77','gender'=>'male','dob'=>'1978-11-05'],
                ['name'=>'Grace Mlay Mfaume','party'=>'SAU','nida'=>'19700118-44444-44444-78','gender'=>'female','dob'=>'1970-01-18'],
            ],'vote_distribution'=>[120,25,15,10]],
            ['name'=>'Mbeya','region'=>'Mbeya','candidates'=>[
                ['name'=>'Joseph Mwasote Lulandala','party'=>'CCM','nida'=>'19750320-11111-11111-79','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Dydimus Akyoo Mwita','party'=>'CHADEMA','nida'=>'19810612-22222-22222-80','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Amina Mwasota Simba','party'=>'CUF','nida'=>'19781105-33333-33333-81','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[135,22,13]],
            ['name'=>'Dodoma','region'=>'Dodoma','candidates'=>[
                ['name'=>'Anthony Mwenda Mwita','party'=>'CCM','nida'=>'19750320-11111-11111-82','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'John Mnyika Mwambene','party'=>'CHADEMA','nida'=>'19810612-22222-22222-83','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Hamida Mwinyi Juma','party'=>'CUF','nida'=>'19781105-33333-33333-84','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[140,25,15]],
            ['name'=>'Kilimanjaro','region'=>'Kilimanjaro','candidates'=>[
                ['name'=>'Rehema Nnauye Mbuguni','party'=>'CCM','nida'=>'19750320-11111-11111-85','gender'=>'female','dob'=>'1975-03-20'],
                ['name'=>'Benedict Mwita Lowasa','party'=>'CHADEMA','nida'=>'19810612-22222-22222-86','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Francis Mushi Lyimo','party'=>'NCCR-Mageuzi','nida'=>'19781105-33333-33333-87','gender'=>'male','dob'=>'1978-11-05'],
                ['name'=>'Anna Mwita Ngowi','party'=>'TLP','nida'=>'19700118-44444-44444-88','gender'=>'female','dob'=>'1970-01-18'],
            ],'vote_distribution'=>[118,30,12,10]],
            ['name'=>'Tanga','region'=>'Tanga','candidates'=>[
                ['name'=>'Muhidini Issa Mwita','party'=>'CCM','nida'=>'19750320-11111-11111-89','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Mariam Juma Mwinyi','party'=>'CUF','nida'=>'19810612-22222-22222-90','gender'=>'female','dob'=>'1981-06-12'],
                ['name'=>'Abdallah Mwita Juma','party'=>'CHADEMA','nida'=>'19781105-33333-33333-91','gender'=>'male','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[140,22,18]],
            ['name'=>'Morogoro','region'=>'Morogoro','candidates'=>[
                ['name'=>'Christina Mwita Mnyele','party'=>'CCM','nida'=>'19750320-11111-11111-92','gender'=>'female','dob'=>'1975-03-20'],
                ['name'=>'Moses Mwita Malisa','party'=>'CHADEMA','nida'=>'19810612-22222-22222-93','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Juma Hassan Mwita','party'=>'CUF','nida'=>'19781105-33333-33333-94','gender'=>'male','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[138,24,18]],
            ['name'=>'Kagera','region'=>'Kagera','candidates'=>[
                ['name'=>'Dotto Jasson Bahemu','party'=>'CCM','nida'=>'19750320-11111-11111-95','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Patricia Mwita Kato','party'=>'CHADEMA','nida'=>'19810612-22222-22222-96','gender'=>'female','dob'=>'1981-06-12'],
                ['name'=>'Said Mwita Kato','party'=>'CUF','nida'=>'19781105-33333-33333-97','gender'=>'male','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[138,22,20]],
            ['name'=>'Mtwara','region'=>'Mtwara','candidates'=>[
                ['name'=>'Mbarouk Mwita Nali','party'=>'CCM','nida'=>'19750320-11111-11111-98','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Hassan Mwinyi Juma','party'=>'CUF','nida'=>'19810612-22222-22222-99','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Zainab Mwita Nali','party'=>'CHADEMA','nida'=>'19781105-33333-33333-00','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[140,22,18]],
            ['name'=>'Tabora','region'=>'Tabora','candidates'=>[
                ['name'=>'Juma Mwita Kimbonge','party'=>'CCM','nida'=>'19750320-11111-11111-01','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Mwanaisha Juma Said','party'=>'CHADEMA','nida'=>'19810612-22222-22222-02','gender'=>'female','dob'=>'1981-06-12'],
                ['name'=>'Said Mwita Ngasa','party'=>'CUF','nida'=>'19781105-33333-33333-03','gender'=>'male','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[118,18,14]],
            ['name'=>'Shinyanga','region'=>'Shinyanga','candidates'=>[
                ['name'=>'Emmanuel Mwita Mwanjoka','party'=>'CCM','nida'=>'19750320-11111-11111-04','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Juma Mwita Shoka','party'=>'CUF','nida'=>'19810612-22222-22222-05','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Mwajuma Mwita Juma','party'=>'CHADEMA','nida'=>'19781105-33333-33333-06','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[120,16,14]],
            ['name'=>'Ruvuma','region'=>'Ruvuma','candidates'=>[
                ['name'=>'Joseph Mwita Nzunda','party'=>'CCM','nida'=>'19750320-11111-11111-07','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Grace Mwita Ngusa','party'=>'CHADEMA','nida'=>'19810612-22222-22222-08','gender'=>'female','dob'=>'1981-06-12'],
                ['name'=>'Hassan Mwita Juma','party'=>'CUF','nida'=>'19781105-33333-33333-09','gender'=>'male','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[120,18,12]],
            ['name'=>'Kigoma','region'=>'Kigoma','candidates'=>[
                ['name'=>'Zahoro Juma Mwita','party'=>'CCM','nida'=>'19750320-11111-11111-10','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Said Mwita Juma','party'=>'CUF','nida'=>'19810612-22222-22222-11','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Neema Mwita Kato','party'=>'CHADEMA','nida'=>'19781105-33333-33333-12','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[118,18,14]],
            ['name'=>'Iringa','region'=>'Iringa','candidates'=>[
                ['name'=>'Abdallah Mwita Mbilinyi','party'=>'CCM','nida'=>'19750320-11111-11111-13','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Mariam Juma Mwita','party'=>'CHADEMA','nida'=>'19810612-22222-22222-14','gender'=>'female','dob'=>'1981-06-12'],
                ['name'=>'Juma Mwita Said','party'=>'CUF','nida'=>'19781105-33333-33333-15','gender'=>'male','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[118,18,14]],
            ['name'=>'Rukwa','region'=>'Rukwa','candidates'=>[
                ['name'=>'Mwanajuma Mwita Simba','party'=>'CCM','nida'=>'19750320-11111-11111-16','gender'=>'female','dob'=>'1975-03-20'],
                ['name'=>'Said Mwita Juma','party'=>'CHADEMA','nida'=>'19810612-22222-22222-17','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Mwajuma Mwita Kato','party'=>'CUF','nida'=>'19781105-33333-33333-18','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[78,12,10]],
            ['name'=>'Geita','region'=>'Geita','candidates'=>[
                ['name'=>'Hassan Mwita Nyange','party'=>'CCM','nida'=>'19750320-11111-11111-19','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Juma Mwita Juma','party'=>'CUF','nida'=>'19810612-22222-22222-20','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Mwanaisha Mwita Simba','party'=>'CHADEMA','nida'=>'19781105-33333-33333-21','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[78,12,10]],
            ['name'=>'Mara','region'=>'Mara','candidates'=>[
                ['name'=>'Mgore Miraji Mwita','party'=>'CCM','nida'=>'19750320-11111-11111-22','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Mwita Mwita Juma','party'=>'CHADEMA','nida'=>'19810612-22222-22222-23','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Mariam Mwita Juma','party'=>'CUF','nida'=>'19781105-33333-33333-24','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[78,12,10]],
            ['name'=>'Njombe','region'=>'Njombe','candidates'=>[
                ['name'=>'Mwita Mwita Mwita','party'=>'CCM','nida'=>'19750320-11111-11111-25','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Said Mwita Kato','party'=>'CHADEMA','nida'=>'19810612-22222-22222-26','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Juma Mwita Said','party'=>'CUF','nida'=>'19781105-33333-33333-27','gender'=>'male','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[78,12,10]],
            ['name'=>'Simiyu','region'=>'Simiyu','candidates'=>[
                ['name'=>'Jumanne Mwita Mwita','party'=>'CCM','nida'=>'19750320-11111-11111-28','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Mwajuma Mwita Juma','party'=>'CUF','nida'=>'19810612-22222-22222-29','gender'=>'female','dob'=>'1981-06-12'],
                ['name'=>'Said Mwita Said','party'=>'CHADEMA','nida'=>'19781105-33333-33333-30','gender'=>'male','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[78,12,10]],
            ['name'=>'Singida','region'=>'Singida','candidates'=>[
                ['name'=>'Mwita Mwita Mwita','party'=>'CCM','nida'=>'19750320-11111-11111-31','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Juma Mwita Mwita','party'=>'CHADEMA','nida'=>'19810612-22222-22222-32','gender'=>'male','dob'=>'1981-06-12'],
                ['name'=>'Mwanaisha Juma Mwita','party'=>'CUF','nida'=>'19781105-33333-33333-33','gender'=>'female','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[78,12,10]],
            ['name'=>'Lindi','region'=>'Lindi','candidates'=>[
                ['name'=>'Hashim Mwita Juma','party'=>'CCM','nida'=>'19750320-11111-11111-34','gender'=>'male','dob'=>'1975-03-20'],
                ['name'=>'Mwajuma Juma Mwita','party'=>'CUF','nida'=>'19810612-22222-22222-35','gender'=>'female','dob'=>'1981-06-12'],
                ['name'=>'Said Mwita Juma','party'=>'CHADEMA','nida'=>'19781105-33333-33333-36','gender'=>'male','dob'=>'1978-11-05'],
            ],'vote_distribution'=>[78,12,10]],
        ];

        $parliamentCandidateIds = [];
        $parlConstituencyMap = [];
        $globalIndex = 0;

        foreach ($parliamentaryConstituencies as $constData) {
            $constituencyName = $constData['name'];
            $constituencyId = $constituencies[$constituencyName] ?? null;
            $constituencyModel = $constituencyId ? Constituency::find($constituencyId) : null;
            $constituencyDisplay = $constituencyModel ? $constituencyModel->name : $constituencyName;

            foreach ($constData['candidates'] as $i => $data) {
                $globalIndex++;
                $email = 'parliamentary.candidate.' . $globalIndex . '@tume.go.tz';
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'full_name' => $data['name'],
                        'phone' => '+2557' . str_pad((600100000 + $globalIndex), 9, '0', STR_PAD_LEFT),
                        'nida_number' => $data['nida'],
                        'password' => 'Candidate@2025',
                        'role' => 'candidate',
                        'is_candidate' => true,
                        'is_voter' => true,
                        'status' => 'active',
                        'age' => Carbon::parse($data['dob'])->age,
                        'is_verified' => true,
                        'email_verified' => true,
                        'language' => 'sw',
                    ]
                );

                $party = PoliticalParty::where('abbreviation', $data['party'])->first();

                $candidate = Candidate::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'election_id' => $parliamentaryElection->id,
                        'position' => 'parliamentary',
                        'full_name' => $data['name'],
                        'gender' => $data['gender'],
                        'date_of_birth' => $data['dob'],
                        'nationality' => 'Tanzanian',
                        'phone' => $user->phone,
                        'email' => $email,
                        'nida_number' => $data['nida'],
                        'party_id' => $party?->id,
                        'party_name' => $party?->name ?? $data['party'],
                        'party_abbreviation' => $data['party'],
                        'constituency_id' => $constituencyId,
                        'constituency' => $constituencyDisplay,
                        'status' => 'approved',
                        'terms_accepted' => true,
                        'approved_at' => Carbon::parse('2025-09-01 00:00:00'),
                        'nomination_submitted_at' => Carbon::parse('2025-08-15 00:00:00'),
                    ]
                );

                $parliamentCandidateIds[] = $candidate->id;
                $parlConstituencyMap[$candidate->id] = [
                    'constituency' => $constituencyDisplay,
                    'vote_distribution' => $constData['vote_distribution'][$i],
                ];
            }
        }

        $this->command->info('Declaring parliamentary winner...');
        $firstParlCandidate = Candidate::where('election_id', $parliamentaryElection->id)
            ->whereHas('user', function ($q) {
                $q->where('email', 'parliamentary.candidate.1@tume.go.tz');
            })->first();
        if ($firstParlCandidate) {
            $parliamentaryElection->update([
                'winner_declared' => true,
                'winner_candidate_id' => $firstParlCandidate->id,
            ]);
        }

        $this->command->info('Creating voter accounts and casting votes...');

        $totalVoters = 5000;
        $passwordHash = bcrypt('Voter@2025');

        $allCandidateIds = Candidate::where('election_id', $presidentialElection->id)
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $parlCandidateIds = Candidate::where('election_id', $parliamentaryElection->id)
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $this->command->info('  Presidential candidates found: ' . count($allCandidateIds));
        $this->command->info('  Parliamentary candidates found: ' . count($parlCandidateIds));

        if (count($allCandidateIds) === 0) {
            $this->command->error('No presidential candidates found! Aborting vote seeding.');
            return;
        }

        $presidentialVoteDistribution = [
            0 => 4883, 1 => 33, 2 => 25, 3 => 15, 4 => 9, 5 => 7,
            6 => 4, 7 => 4, 8 => 4, 9 => 3, 10 => 3, 11 => 2,
            12 => 2, 13 => 2, 14 => 2, 15 => 2, 16 => 2,
        ];

        $presCandidateAssignment = [];
        foreach ($presidentialVoteDistribution as $candidateIdx => $count) {
            $cid = $allCandidateIds[$candidateIdx % count($allCandidateIds)] ?? $allCandidateIds[0];
            for ($k = 0; $k < $count; $k++) {
                $presCandidateAssignment[] = $cid;
            }
        }
        shuffle($presCandidateAssignment);

        $parlCandidateAssignment = [];
        foreach ($parlConstituencyMap as $cid => $info) {
            $count = $info['vote_distribution'];
            for ($k = 0; $k < $count; $k++) {
                $parlCandidateAssignment[] = $cid;
            }
        }
        shuffle($parlCandidateAssignment);

        $batchSize = 100;

        $userEmailBase = 'voter2025.';
        $existingCount = User::where('email', 'like', $userEmailBase . '%')->count();
        $startIndex = $existingCount;

        $this->command->info("Creating $totalVoters voters in batches of $batchSize...");

        $sampleTimestamps = [];
        for ($h = 7; $h <= 15; $h++) {
            for ($m = 0; $m < 60; $m += 15) {
                $sampleTimestamps[] = Carbon::parse("2025-10-29 $h:$m:00");
            }
        }

        for ($batch = 0; $batch < $totalVoters; $batch += $batchSize) {
            $size = min($batchSize, $totalVoters - $batch);
            $userRows = [];
            $voteRows = [];

            for ($j = 0; $j < $size; $j++) {
                $idx = $startIndex + $batch + $j;
                $nidaNum = str_pad(100000 + $idx, 5, '0', STR_PAD_LEFT);

                $userRows[] = [
                    'full_name' => 'Voter ' . ($idx + 1),
                    'email' => $userEmailBase . ($idx + 1) . '@example.com',
                    'phone' => '+2557' . str_pad((700000000 + $idx), 9, '0', STR_PAD_LEFT),
                    'nida_number' => '19900101-' . substr($nidaNum, 0, 5) . '-' . substr($nidaNum, 0, 5) . '-' . str_pad($idx % 100, 2, '0', STR_PAD_LEFT),
                    'password' => $passwordHash,
                    'role' => 'voter',
                    'is_voter' => 1,
                    'is_candidate' => 0,
                    'status' => 'active',
                    'age' => rand(18, 85),
                    'is_verified' => 1,
                    'email_verified' => 1,
                    'language' => 'sw',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('users')->insertOrIgnore($userRows);

            $emails = array_column($userRows, 'email');
            $createdUsers = User::whereIn('email', $emails)->pluck('id', 'email')->toArray();

            $voterBatchIdx = $batch;
            foreach ($emails as $email) {
                $userId = $createdUsers[$email] ?? null;
                if (!$userId) continue;

                $ts = $sampleTimestamps[array_rand($sampleTimestamps)];

                $presCandId = $presCandidateAssignment[$voterBatchIdx] ?? $allCandidateIds[0];
                $parlCandId = $parlCandidateAssignment[$voterBatchIdx] ?? $parlCandidateIds[0];
                $voterBatchIdx++;

                $voteHashPres = hash('sha256', $userId . '_' . $presidentialElection->id . '_' . config('app.key'));
                $voteHashParl = hash('sha256', $userId . '_' . $parliamentaryElection->id . '_' . config('app.key'));

                $voteRows[] = [
                    'user_id' => $userId,
                    'candidate_id' => $presCandId,
                    'election_id' => $presidentialElection->id,
                    'timestamp' => $ts,
                    'vote_hash' => $voteHashPres,
                ];

                $voteRows[] = [
                    'user_id' => $userId,
                    'candidate_id' => $parlCandId,
                    'election_id' => $parliamentaryElection->id,
                    'timestamp' => $ts,
                    'vote_hash' => $voteHashParl,
                ];
            }

            foreach ($voteRows as $vr) {
                Vote::create($vr);
            }

            if (($batch + $size) % 500 === 0 || ($batch + $size) >= $totalVoters) {
                $this->command->info('  ... ' . ($batch + $size) . ' / ' . $totalVoters . ' voters processed');
            }
        }

        $presVoteCount = Vote::where('election_id', $presidentialElection->id)->count();
        $parlVoteCount = Vote::where('election_id', $parliamentaryElection->id)->count();
        $totalCandidates = Candidate::count();

        $this->command->info('');
        $this->command->info('=== 2025 Election Seeding Complete ===');
        $this->command->info("Presidential votes: $presVoteCount");
        $this->command->info("Parliamentary votes: $parlVoteCount");
        $this->command->info("Total candidates created: $totalCandidates");
        $this->command->info('Presidential winner: Samia Suluhu Hassan (CCM) — 97.7%');
        $this->command->info('Parliamentary winner: Mbarouk Ali Mbarouk (CCM, Ilala) — 85%');
        $this->command->info('');
    }
}
