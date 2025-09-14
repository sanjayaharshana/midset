<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            // Banking & Financial Services
            [
                'name' => 'Commercial Bank of Ceylon PLC',
                'short_code' => 'CBC',
                'company_name' => 'Commercial Bank of Ceylon PLC',
                'contact_person' => 'Mr. Suresh Perera',
                'email' => 'info@combank.net',
                'phone' => '+94 11 244 4444',
                'company_address' => 'Commercial Bank Tower, 21 Sir Razik Fareed Mawatha, Colombo 01',
                'status' => 'active'
            ],
            [
                'name' => 'People\'s Bank',
                'short_code' => 'PB',
                'company_name' => 'People\'s Bank',
                'contact_person' => 'Ms. Priyanka Fernando',
                'email' => 'info@peoplesbank.lk',
                'phone' => '+94 11 220 4444',
                'company_address' => 'No. 75, Sir Chittampalam A. Gardiner Mawatha, Colombo 02',
                'status' => 'active'
            ],
            [
                'name' => 'Sampath Bank PLC',
                'short_code' => 'SB',
                'company_name' => 'Sampath Bank PLC',
                'contact_person' => 'Mr. Ravi Jayasuriya',
                'email' => 'info@sampath.lk',
                'phone' => '+94 11 230 0000',
                'company_address' => 'No. 110, Sir James Peiris Mawatha, Colombo 02',
                'status' => 'active'
            ],
            [
                'name' => 'Hatton National Bank PLC',
                'short_code' => 'HNB',
                'company_name' => 'Hatton National Bank PLC',
                'contact_person' => 'Ms. Nimali Silva',
                'email' => 'info@hnb.lk',
                'phone' => '+94 11 266 4664',
                'company_address' => 'HNB Towers, 479 T.B. Jayah Mawatha, Colombo 10',
                'status' => 'active'
            ],

            // Telecommunications
            [
                'name' => 'Dialog Axiata PLC',
                'short_code' => 'DIA',
                'company_name' => 'Dialog Axiata PLC',
                'contact_person' => 'Mr. Ashantha De Silva',
                'email' => 'info@dialog.lk',
                'phone' => '+94 11 777 7777',
                'company_address' => 'Dialog Axiata Centre, 475 Union Place, Colombo 02',
                'status' => 'active'
            ],
            [
                'name' => 'Mobitel (Pvt) Ltd',
                'short_code' => 'MOB',
                'company_name' => 'Mobitel (Pvt) Ltd',
                'contact_person' => 'Ms. Chamari Wickramasinghe',
                'email' => 'info@mobitel.lk',
                'phone' => '+94 11 777 8888',
                'company_address' => 'Mobitel House, 200 Deans Road, Colombo 10',
                'status' => 'active'
            ],
            [
                'name' => 'Hutchison Telecommunications Lanka (Pvt) Ltd',
                'short_code' => 'HUT',
                'company_name' => 'Hutchison Telecommunications Lanka (Pvt) Ltd',
                'contact_person' => 'Mr. Dinesh Karunaratne',
                'email' => 'info@hutch.lk',
                'phone' => '+94 11 777 9999',
                'company_address' => 'Hutchison House, 200 Deans Road, Colombo 10',
                'status' => 'active'
            ],

            // Retail & Supermarkets
            [
                'name' => 'Cargills (Ceylon) PLC',
                'short_code' => 'CAR',
                'company_name' => 'Cargills (Ceylon) PLC',
                'contact_person' => 'Ms. Sanduni Perera',
                'email' => 'info@cargillsceylon.com',
                'phone' => '+94 11 230 3000',
                'company_address' => 'Cargills Square, 40 York Street, Colombo 01',
                'status' => 'active'
            ],
            [
                'name' => 'Keells Super',
                'short_code' => 'KEE',
                'company_name' => 'Keells Super',
                'contact_person' => 'Mr. Nimal Rajapaksa',
                'email' => 'info@keells.com',
                'phone' => '+94 11 230 4000',
                'company_address' => 'John Keells Holdings PLC, 117 Sir Chittampalam A. Gardiner Mawatha, Colombo 02',
                'status' => 'active'
            ],
            [
                'name' => 'Arpico Supercentres',
                'short_code' => 'ARP',
                'company_name' => 'Arpico Supercentres',
                'contact_person' => 'Ms. Tharanga Gunasekara',
                'email' => 'info@arpico.com',
                'phone' => '+94 11 230 5000',
                'company_address' => 'Arpico House, 73 Dharmapala Mawatha, Colombo 07',
                'status' => 'active'
            ],

            // Manufacturing & FMCG
            [
                'name' => 'Ceylon Tea Services PLC',
                'short_code' => 'CTS',
                'company_name' => 'Ceylon Tea Services PLC',
                'contact_person' => 'Mr. Ranjith Wijesinghe',
                'email' => 'info@ceylontea.lk',
                'phone' => '+94 11 230 6000',
                'company_address' => 'No. 1, Galle Face Terrace, Colombo 03',
                'status' => 'active'
            ],
            [
                'name' => 'Ceylon Biscuits Limited',
                'short_code' => 'CBL',
                'company_name' => 'Ceylon Biscuits Limited',
                'contact_person' => 'Ms. Dilani Perera',
                'email' => 'info@munchee.lk',
                'phone' => '+94 11 230 7000',
                'company_address' => 'CBL House, 400 Deans Road, Colombo 10',
                'status' => 'active'
            ],
            [
                'name' => 'Unilever Sri Lanka',
                'short_code' => 'UNI',
                'company_name' => 'Unilever Sri Lanka',
                'contact_person' => 'Mr. Sajith Wickramasinghe',
                'email' => 'info@unilever.lk',
                'phone' => '+94 11 230 8000',
                'company_address' => 'Unilever House, 400 Deans Road, Colombo 10',
                'status' => 'active'
            ],
            [
                'name' => 'Nestle Lanka PLC',
                'short_code' => 'NES',
                'company_name' => 'Nestle Lanka PLC',
                'contact_person' => 'Ms. Priyanka Jayawardena',
                'email' => 'info@nestle.lk',
                'phone' => '+94 11 230 9000',
                'company_address' => 'Nestle Lanka PLC, 400 Deans Road, Colombo 10',
                'status' => 'active'
            ],

            // Healthcare & Pharmaceuticals
            [
                'name' => 'Ceylon Hospitals PLC',
                'short_code' => 'CHP',
                'company_name' => 'Ceylon Hospitals PLC',
                'contact_person' => 'Dr. Nimali Fernando',
                'email' => 'info@ceylonhospitals.lk',
                'phone' => '+94 11 231 0000',
                'company_address' => 'Ceylon Hospitals, 114 Norris Canal Road, Colombo 10',
                'status' => 'active'
            ],
            [
                'name' => 'Asiri Hospital Holdings PLC',
                'short_code' => 'ASH',
                'company_name' => 'Asiri Hospital Holdings PLC',
                'contact_person' => 'Dr. Ravi Perera',
                'email' => 'info@asiri.lk',
                'phone' => '+94 11 231 1000',
                'company_address' => 'Asiri Central Hospital, 181 Kirula Road, Colombo 05',
                'status' => 'active'
            ],
            [
                'name' => 'Lanka Hospitals Corporation PLC',
                'short_code' => 'LHC',
                'company_name' => 'Lanka Hospitals Corporation PLC',
                'contact_person' => 'Dr. Sanduni Silva',
                'email' => 'info@lankahospitals.com',
                'phone' => '+94 11 231 2000',
                'company_address' => 'Lanka Hospitals, 578 Elvitigala Mawatha, Colombo 05',
                'status' => 'active'
            ],

            // Insurance
            [
                'name' => 'Ceylinco Insurance PLC',
                'short_code' => 'CIN',
                'company_name' => 'Ceylinco Insurance PLC',
                'contact_person' => 'Mr. Nimal Rajapaksa',
                'email' => 'info@ceylinco.lk',
                'phone' => '+94 11 231 3000',
                'company_address' => 'Ceylinco House, 69 Janadhipathi Mawatha, Colombo 01',
                'status' => 'active'
            ],
            [
                'name' => 'Sri Lanka Insurance Corporation',
                'short_code' => 'SLI',
                'company_name' => 'Sri Lanka Insurance Corporation',
                'contact_person' => 'Ms. Tharanga Gunasekara',
                'email' => 'info@srilankainsurance.lk',
                'phone' => '+94 11 231 4000',
                'company_address' => 'SLIC House, 21 Vauxhall Street, Colombo 02',
                'status' => 'active'
            ],
            [
                'name' => 'Allianz Insurance Lanka Ltd',
                'short_code' => 'ALZ',
                'company_name' => 'Allianz Insurance Lanka Ltd',
                'contact_person' => 'Mr. Dinesh Karunaratne',
                'email' => 'info@allianz.lk',
                'phone' => '+94 11 231 5000',
                'company_address' => 'Allianz House, 400 Deans Road, Colombo 10',
                'status' => 'active'
            ],

            // Construction & Real Estate
            [
                'name' => 'Access Engineering PLC',
                'short_code' => 'ACE',
                'company_name' => 'Access Engineering PLC',
                'contact_person' => 'Mr. Ravi Jayasuriya',
                'email' => 'info@accesseng.lk',
                'phone' => '+94 11 231 6000',
                'company_address' => 'Access Engineering PLC, 278 Union Place, Colombo 02',
                'status' => 'active'
            ],
            [
                'name' => 'LOLC Holdings PLC',
                'short_code' => 'LOL',
                'company_name' => 'LOLC Holdings PLC',
                'contact_person' => 'Ms. Nimali Silva',
                'email' => 'info@lolc.lk',
                'phone' => '+94 11 231 7000',
                'company_address' => 'LOLC House, 100/1, Sri Jayawardenepura Mawatha, Rajagiriya',
                'status' => 'active'
            ],
            [
                'name' => 'Ceylon Cold Stores PLC',
                'short_code' => 'CCS',
                'company_name' => 'Ceylon Cold Stores PLC',
                'contact_person' => 'Mr. Ashantha De Silva',
                'email' => 'info@ccs.lk',
                'phone' => '+94 11 231 8000',
                'company_address' => 'CCS House, 400 Deans Road, Colombo 10',
                'status' => 'active'
            ],

            // Tourism & Hospitality
            [
                'name' => 'John Keells Hotels PLC',
                'short_code' => 'JKH',
                'company_name' => 'John Keells Hotels PLC',
                'contact_person' => 'Ms. Chamari Wickramasinghe',
                'email' => 'info@johnkeellshotels.com',
                'phone' => '+94 11 231 9000',
                'company_address' => 'John Keells Holdings PLC, 117 Sir Chittampalam A. Gardiner Mawatha, Colombo 02',
                'status' => 'active'
            ],
            [
                'name' => 'Jetwing Hotels Ltd',
                'short_code' => 'JET',
                'company_name' => 'Jetwing Hotels Ltd',
                'contact_person' => 'Mr. Suresh Perera',
                'email' => 'info@jetwinghotels.com',
                'phone' => '+94 11 232 0000',
                'company_address' => 'Jetwing House, 46/26 Navam Mawatha, Colombo 02',
                'status' => 'active'
            ],
            [
                'name' => 'Cinnamon Hotels & Resorts',
                'short_code' => 'CMN',
                'company_name' => 'Cinnamon Hotels & Resorts',
                'contact_person' => 'Ms. Priyanka Fernando',
                'email' => 'info@cinnamonhotels.com',
                'phone' => '+94 11 232 1000',
                'company_address' => 'Cinnamon Grand Colombo, 77 Galle Road, Colombo 03',
                'status' => 'active'
            ],

            // IT & Technology
            [
                'name' => 'WSO2 Lanka (Pvt) Ltd',
                'short_code' => 'WSO',
                'company_name' => 'WSO2 Lanka (Pvt) Ltd',
                'contact_person' => 'Ms. Nimali Silva',
                'email' => 'info@wso2.com',
                'phone' => '+94 11 232 7000',
                'company_address' => 'WSO2 Lanka, 4th Floor, 160/24, Kirimandala Mawatha, Colombo 05',
                'status' => 'active'
            ],
            [
                'name' => 'Virtusa (Pvt) Ltd',
                'short_code' => 'VIR',
                'company_name' => 'Virtusa (Pvt) Ltd',
                'contact_person' => 'Mr. Ashantha De Silva',
                'email' => 'info@virtusa.com',
                'phone' => '+94 11 232 8000',
                'company_address' => 'Virtusa Corporation, 2000 Deans Road, Colombo 10',
                'status' => 'active'
            ],
            [
                'name' => 'IFS R&D International (Pvt) Ltd',
                'short_code' => 'IFS',
                'company_name' => 'IFS R&D International (Pvt) Ltd',
                'contact_person' => 'Ms. Chamari Wickramasinghe',
                'email' => 'info@ifs.com',
                'phone' => '+94 11 232 9000',
                'company_address' => 'IFS Global Headquarters, 2000 Deans Road, Colombo 10',
                'status' => 'active'
            ],

            // Textile & Apparel
            [
                'name' => 'Brandix Lanka Ltd',
                'short_code' => 'BRA',
                'company_name' => 'Brandix Lanka Ltd',
                'contact_person' => 'Ms. Tharanga Gunasekara',
                'email' => 'info@brandix.com',
                'phone' => '+94 11 233 5000',
                'company_address' => 'Brandix Lanka Ltd, 2000 Deans Road, Colombo 10',
                'status' => 'active'
            ],
            [
                'name' => 'MAS Holdings (Pvt) Ltd',
                'short_code' => 'MAS',
                'company_name' => 'MAS Holdings (Pvt) Ltd',
                'contact_person' => 'Mr. Ravi Jayasuriya',
                'email' => 'info@masholdings.com',
                'phone' => '+94 11 233 6000',
                'company_address' => 'MAS Holdings, 2000 Deans Road, Colombo 10',
                'status' => 'active'
            ],
            [
                'name' => 'Hirdaramani Group',
                'short_code' => 'HIR',
                'company_name' => 'Hirdaramani Group',
                'contact_person' => 'Ms. Nimali Silva',
                'email' => 'info@hirdaramani.com',
                'phone' => '+94 11 233 7000',
                'company_address' => 'Hirdaramani Group, 2000 Deans Road, Colombo 10',
                'status' => 'active'
            ]
        ];

        foreach ($clients as $clientData) {
            Client::firstOrCreate(
                ['email' => $clientData['email']],
                $clientData
            );
        }

        $this->command->info('Successfully seeded ' . count($clients) . ' Sri Lankan companies!');
    }
}