<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Announcement;
use App\Models\Objection;
use App\Models\AuditLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ElectionDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        $this->command->info('Seeding election scenarios...');

        $this->seedLuhagaMpina($admin);
        $this->seedAnnouncement($admin);
        $this->seedObjections();
    }

    protected function seedLuhagaMpina($admin)
    {
        $existing = User::where('email', 'luhaga.mpina@example.com')->first();
        if ($existing) {
            $candidate = Candidate::where('user_id', $existing->id)->first();
            if ($candidate && $candidate->party_abbreviation !== 'ACT-Wazalendo') {
                $candidate->update([
                    'party_name' => 'Alliance for Change and Transparency (ACT-Wazalendo)',
                    'party_abbreviation' => 'ACT-Wazalendo',
                ]);
                $this->command->info('  Luhaga Mpina party updated to ACT-Wazalendo.');
            } else {
                $this->command->info('  Luhaga Mpina already exists, skipping.');
            }
            return;
        }

        $user = User::create([
            'full_name' => 'Luhaga Mpina',
            'email' => 'luhaga.mpina@example.com',
            'phone' => '+255713456789',
            'nida_number' => '19700315-12345-67890-12',
            'password' => bcrypt('Candidate@2025'),
            'role' => 'voter',
            'is_voter' => true,
            'is_candidate' => false,
            'status' => 'active',
            'age' => 55,
            'is_verified' => true,
            'email_verified' => true,
            'language' => 'sw',
        ]);

        $presidentialElection = Election::where('election_type', 'presidential')->first();
        $parliamentaryElection = Election::where('election_type', 'parliamentary')->first();

        $electionId = $presidentialElection?->id ?? 1;

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'election_id' => $electionId,
            'position' => 'presidential',
            'full_name' => 'Luhaga Mpina',
            'gender' => 'male',
            'date_of_birth' => '1970-03-15',
            'nationality' => 'Tanzanian',
            'phone' => $user->phone,
            'email' => $user->email,
            'nida_number' => $user->nida_number,
            'party_name' => 'Alliance for Change and Transparency (ACT-Wazalendo)',
            'party_abbreviation' => 'ACT-Wazalendo',
            'status' => 'rejected',
            'rejection_reason' => 'Mgombea hakuwa na sifa za kutosha za kugombea urais kwa mujibu wa Katiba ya Jamhuri ya Muungano wa Tanzania Ibara ya 39(1)(c)(ii) na Sheria ya Uchaguzi ya Mwaka 2010 Kifungu cha 35(1) kutokana na kukosa uraia halisi wa Tanzania na kushindwa kutoa uthibitisho wa kutokuwa na deni la kodi kwa Serikali. Pia, Tume ilibaini kuwepo kwa dosari katika nyaraka zake za uraia na utofauti wa majina kwenye vyeti vyake vya kuzaliwa na vyuo alivyodai kuhudhuria. / The candidate did not meet the constitutional qualifications for presidency under Article 39(1)(c)(ii) of the Constitution of the United Republic of Tanzania and Section 35(1) of the Election Act of 2010 due to lack of valid Tanzanian citizenship and failure to provide proof of tax clearance. The Commission also identified discrepancies in his citizenship documentation and inconsistencies in names on his birth certificate and academic credentials.',
            'terms_accepted' => true,
            'approved_at' => null,
            'nomination_submitted_at' => Carbon::parse('2025-08-10 00:00:00'),
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'CANDIDATE_REJECTED',
            'details' => "Luhaga Mpina rejected: {$candidate->rejection_reason}",
            'ip_address' => '127.0.0.1',
            'device_info' => 'System Seeder',
            'timestamp' => Carbon::parse('2025-08-20 14:30:00'),
        ]);

        $this->command->info('  Luhaga Mpina seeded as rejected candidate.');
    }

    protected function seedAnnouncement($admin)
    {
        $existing = Announcement::where('title_en', 'Official Statement on the Conduct of the 2025 General Election')->first();
        if ($existing) {
            $this->command->info('  Election day announcement already exists, skipping.');
            return;
        }

        Announcement::create([
            'title_en' => 'Official Statement on the Conduct of the 2025 General Election',
            'title_sw' => 'Tamko Rasmi Kuhusu Maadhimisho ya Uchaguzi Mkuu wa 2025',
            'content_en' => "The National Electoral Commission (INEC) hereby issues the following statement regarding the conduct of the 2025 General Election held on 29th October 2025:\n\n1. HIGH VOTER TURNOUT: The election witnessed an unprecedented voter turnout across all 27 constituencies of mainland Tanzania, with over 85% of registered voters casting their ballots.\n\n2. PEACEFUL PROCESS: The voting process was largely peaceful, with minor logistical delays reported in 6 constituencies due to late arrival of voting materials. These delays were resolved within 2 hours.\n\n3. TECHNICAL INCIDENTS: There were 3 reported cases of electronic voting machine malfunctions at polling stations in Temeke, Arusha, and Mbeya. All affected machines were replaced within 30 minutes, and affected voters were allowed to cast their votes.\n\n4. SECURITY INCIDENTS: Two individuals were arrested in Dodoma for attempting to vote multiple times. The matter has been handed over to the police for further investigation.\n\n5. OBJECTION PERIOD: Following the closure of voting, the Commission has opened a 7-day objection period as mandated by law. Voters who have valid concerns may submit their objections through the official portal.\n\nThe Commission commends all Tanzanians for their peaceful participation in this democratic exercise and assures the public that all votes will be counted transparently and accurately.",
            'content_sw' => "Tume Huru ya Taifa ya Uchaguzi (INEC) inatoa tamko lifuatalo kuhusu maadhimisho ya Uchaguzi Mkuu wa 2025 uliofanyika tarehe 29 Oktoba 2025:\n\n1. IDADI KUBWA YA WAPIGA KURA: Uchaguzi ulishuhudia idadi kubwa ya wapiga kura katika majimbo yote 27 ya Tanzania Bara, na zaidi ya asilimia 85 ya wapiga kura waliosajiliwa walipiga kura zao.\n\n2. MCHAKATO WA AMANI: Mchakato wa upigaji kura ulikuwa wa amani kwa kiasi kikubwa, na ucheleweshaji mdogo wa vifaa vya upigaji kura uliripotiwa katika majimbo 6 ambao ulitatuliwa ndani ya muda wa saa 2.\n\n3. MATATIZO YA KIUCHUMI: Kulikuwa na matatizo matatu ya vifaa vya kieletroniki vya upigaji kura katika vituo vya kupigia kura Temeke, Arusha, na Mbeya. Vifaa vyote vilivyoathirika vilibadilishwa ndani ya dakika 30, na wapiga kura walioathirika waliruhusiwa kupiga kura.\n\n4. MATUKIO YA USALAMA: Watu wawili walikamatwa Dodoma kwa kujaribu kupiga kura mara mbili. Jambo hilo limekabidhiwa kwa polisi kwa ajili ya uchunguzi zaidi.\n\n5. KIPINDI CHA MALEZI: Kufuatia kufungwa kwa upigaji kura, Tume imefungua kipindi cha siku 7 cha malezi kama ilivyoagizwa na sheria. Wapiga kura wenye wasiwasi halali wanaweza kuwasilisha malalamiko yao kupitia tovuti rasmi.\n\nTume inawashukuru Watanzania wote kwa ushiriki wao wa amani katika zoezi hili la kidemokrasia na inawahakikishia umma kwamba kura zote zitahesabiwa kwa uwazi na usahihi.",
            'priority' => 'urgent',
            'is_published' => true,
            'created_by' => $admin->id,
            'published_at' => Carbon::parse('2025-10-30 10:00:00'),
        ]);

        $this->command->info('  Election day announcement seeded.');
    }

    protected function seedObjections()
    {
        $election = Election::where('election_type', 'presidential')->first();
        if (!$election) {
            $this->command->info('  No presidential election found, skipping objections.');
            return;
        }

        $voters = User::where('is_voter', true)
            ->where('role', 'voter')
            ->where('email', 'like', 'voter2025.%')
            ->take(5)
            ->get();

        if ($voters->count() < 5) {
            $this->command->info('  Not enough voters found, skipping objections.');
            return;
        }

        $existingCount = Objection::where('type', 'election')
            ->where('election_id', $election->id)
            ->count();

        if ($existingCount >= 5) {
            $this->command->info('  Objections already exist, skipping.');
            return;
        }

        $objections = [
            [
                'reason' => "Tunapinga matokeo ya uchaguzi wa urais kwa madai kwamba kulikuwa na upendeleo wa CCM katika upigaji kura. Wapiga kura wengi walinyimwa haki yao ya kupiga kura kwa kukosa kupata vitambulisho vya mpiga kura. Tunamtaka Samia Suluhu Hassan ajiuzulu kwa sababu uchaguzi haukuwa huru na wa haki. / We challenge the presidential election results alleging CCM bias in the voting process. Many voters were denied their right to vote due to failure to obtain voter identification. We demand Samia Suluhu Hassan step down because the election was not free and fair.",
                'evidence' => "Tunazo orodha ya wapiga kura 47 ambao walikataliwa kupiga kura katika jimbo la Arusha. Pia tuna picha za vituo vya kupigia kura vilivyokuwa na vifaa vibovu. / We have a list of 47 voters who were denied voting in Arusha constituency. We also have photos of polling stations with faulty equipment."
            ],
            [
                'reason' => "Kuna ushahidi wa ubadhirifu wa kura katika uchaguzi huu. Tuliona watu wakipiga kura mara mbili na baadhi ya maafisa wa TUME waliruhusu hili kutokea. Tunadai Samia Suluhu Hassan aanguke kwa sababu ushindi wake umechafuka na kutokuwa halali. / There is evidence of vote rigging in this election. We saw people voting multiple times and some NEC officials allowed this to happen. We demand Samia Suluhu Hassan step down because her victory is tainted and illegitimate.",
                'evidence' => "Tunazo video za watu wakipiga kura zaidi ya mara moja katika jimbo la Ubungo, pamoja na ripoti za polisi. / We have videos of people voting more than once in Ubungo constituency, along with police reports."
            ],
            [
                'reason' => "Chaguzi hazikuwa huru kwa sababu CCM ilitumia utawala kuwashawishi wapiga kura. Pia kulikuwa na matukio ya vitisho kwa wapiga kura waliohitaji kupiga kura kwa wagombea wa upinzani. Tunamtaka Rais Samia Suluhu Hassan ajiuzulu na uchaguzi mpya ufanyike chini ya uangalizi wa kimataifa. / The elections were not free because CCM used state resources to influence voters. There were also incidents of intimidation against voters who wanted to vote for opposition candidates. We demand President Samia Suluhu Hassan resign and a new election be held under international supervision.",
                'evidence' => "Tunazo ripoti kutoka kwa mashirika ya kiraia yaliyokuwa yakifuatilia uchaguzi, zikionyesha matukio 23 ya vitisho kwa wapiga kura katika kanda za Mbeya, Mwanza, na Dodoma. / We have reports from civil society organizations monitoring the election, showing 23 incidents of voter intimidation in Mbeya, Mwanza, and Dodoma regions."
            ],
            [
                'reason' => "Tunapinga kwa nguvu zote matokeo ya uchaguzi kwa sababu idadi ya kura zilizotolewa kwa Samia Suluhu Hassan inaonekana kubwa sana ikilinganishwa na takwimu za awali. Hili linatilia shaka uhalali wa uchaguzi mzima. Tunamwomba Samia Suluhu Hassan ajiuzulu ili kuruhusu uchunguzi huru wa matokeo haya. / We strongly challenge the election results because the number of votes given to Samia Suluhu Hassan appears too high compared to previous statistics. This casts doubt on the legitimacy of the entire election. We ask Samia Suluhu Hassan to step down to allow an independent investigation of these results.",
                'evidence' => "Tunalinganisha takwimu za idadi ya wapiga kura waliosajiliwa na kura zilizopigwa, na tunaona tofauti kubwa katika maeneo kadhaa. Takwimu zetu zinaonyesha kuwa zaidi ya asilimia 97% ya kura kwa Samia hazilingani na hali halisi ya kisiasa nchini. / We compare registered voter statistics with votes cast, and we see large discrepancies in several areas. Our data shows that over 97% of votes for Samia do not match the actual political situation in the country."
            ],
            [
                'reason' => "Uchaguzi huu haukukidhi viwango vya kimataifa vya uchaguzi huru na wa haki. Watazamaji wa kimataifa walibaini makosa kadhaa katika mchakato wa upigaji kura na kuhesabu kura. Kwa sababu hii tunadai Samia Suluhu Hassan ajiuzulu mara moja na serikali ya mpito iundwe kuandaa uchaguzi mpya. / This election did not meet international standards for free and fair elections. International observers identified several irregularities in the voting and counting process. For this reason we demand Samia Suluhu Hassan step down immediately and a transitional government be formed to prepare a new election.",
                'evidence' => "Ripoti ya awali kutoka kwa watazamaji wa uchaguzi kutoka Jumuiya ya Maendeleo ya Kusini mwa Afrika (SADC) na Jumuiya ya Afrika Mashariki (EAC) zinaonyesha dosari kadhaa katika mchakato wa uchapishaji na usambazaji wa kura. / Preliminary reports from election observers from SADC and EAC indicate several irregularities in the printing and distribution of ballot papers."
            ],
        ];

        foreach ($objections as $i => $obj) {
            $voter = $voters[$i];
            $exists = Objection::where('objector_id', $voter->id)
                ->where('election_id', $election->id)
                ->where('type', 'election')
                ->exists();

            if ($exists) continue;

            Objection::create([
                'type' => 'election',
                'objector_id' => $voter->id,
                'candidate_id' => null,
                'election_id' => $election->id,
                'reason' => $obj['reason'],
                'evidence' => $obj['evidence'],
                'status' => 'pending',
            ]);
        }

        $this->command->info('  Objections seeded (5 objections from voters).');
    }
}
