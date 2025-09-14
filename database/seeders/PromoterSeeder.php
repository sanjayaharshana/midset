<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promoter;
use App\Models\PromoterPosition;

class PromoterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create promoter positions
        $positions = [
            [
                'position_name' => 'Girl Promoter',
                'description' => 'Female promoter for beauty and lifestyle products',
                'status' => 'active'
            ],
            [
                'position_name' => 'Boy Promoter',
                'description' => 'Male promoter for general products and services',
                'status' => 'active'
            ],
            [
                'position_name' => 'Senior Promoter',
                'description' => 'Experienced promoter with leadership responsibilities',
                'status' => 'active'
            ],
            [
                'position_name' => 'Team Leader',
                'description' => 'Promoter responsible for managing a team of promoters',
                'status' => 'active'
            ],
            [
                'position_name' => 'Brand Ambassador',
                'description' => 'High-level promoter representing specific brands',
                'status' => 'active'
            ],
            [
                'position_name' => 'Event Promoter',
                'description' => 'Specialized promoter for events and exhibitions',
                'status' => 'active'
            ],
            [
                'position_name' => 'Retail Promoter',
                'description' => 'Promoter working in retail environments',
                'status' => 'active'
            ],
            [
                'position_name' => 'Field Promoter',
                'description' => 'Promoter working in outdoor field locations',
                'status' => 'active'
            ]
        ];

        foreach ($positions as $positionData) {
            PromoterPosition::firstOrCreate(
                ['position_name' => $positionData['position_name']],
                $positionData
            );
        }

        $this->command->info('Created ' . count($positions) . ' promoter positions');

        // Get position IDs for reference
        $girlPosition = PromoterPosition::where('position_name', 'Girl Promoter')->first();
        $boyPosition = PromoterPosition::where('position_name', 'Boy Promoter')->first();
        $seniorPosition = PromoterPosition::where('position_name', 'Senior Promoter')->first();
        $teamLeaderPosition = PromoterPosition::where('position_name', 'Team Leader')->first();
        $brandAmbassadorPosition = PromoterPosition::where('position_name', 'Brand Ambassador')->first();
        $eventPosition = PromoterPosition::where('position_name', 'Event Promoter')->first();
        $retailPosition = PromoterPosition::where('position_name', 'Retail Promoter')->first();
        $fieldPosition = PromoterPosition::where('position_name', 'Field Promoter')->first();

        // Create promoters
        $promoters = [
            // Girl Promoters
            [
                'promoter_id' => 'P001',
                'position_id' => $girlPosition->id,
                'promoter_name' => 'Poornima Diwyanjali Bandara',
                'identity_card_no' => '199512345678',
                'phone_no' => '+94 77 123 4567',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 07',
                'bank_account_number' => '1234567890',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P002',
                'position_id' => $girlPosition->id,
                'promoter_name' => 'Sanduni Perera',
                'identity_card_no' => '199612345679',
                'phone_no' => '+94 77 234 5678',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 03',
                'bank_account_number' => '2345678901',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P003',
                'position_id' => $girlPosition->id,
                'promoter_name' => 'Tharanga Gunasekara',
                'identity_card_no' => '199712345680',
                'phone_no' => '+94 77 345 6789',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 05',
                'bank_account_number' => '3456789012',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P004',
                'position_id' => $girlPosition->id,
                'promoter_name' => 'Nimali Silva',
                'identity_card_no' => '199812345681',
                'phone_no' => '+94 77 456 7890',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 02',
                'bank_account_number' => '4567890123',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P005',
                'position_id' => $girlPosition->id,
                'promoter_name' => 'Chamari Wickramasinghe',
                'identity_card_no' => '199912345682',
                'phone_no' => '+94 77 567 8901',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 01',
                'bank_account_number' => '5678901234',
                'status' => 'active'
            ],

            // Boy Promoters
            [
                'promoter_id' => 'P006',
                'position_id' => $boyPosition->id,
                'promoter_name' => 'Ravi Jayasuriya',
                'identity_card_no' => '199012345683',
                'phone_no' => '+94 77 678 9012',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 04',
                'bank_account_number' => '6789012345',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P007',
                'position_id' => $boyPosition->id,
                'promoter_name' => 'Suresh Perera',
                'identity_card_no' => '199112345684',
                'phone_no' => '+94 77 789 0123',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 06',
                'bank_account_number' => '7890123456',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P008',
                'position_id' => $boyPosition->id,
                'promoter_name' => 'Dinesh Karunaratne',
                'identity_card_no' => '199212345685',
                'phone_no' => '+94 77 890 1234',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 08',
                'bank_account_number' => '8901234567',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P009',
                'position_id' => $boyPosition->id,
                'promoter_name' => 'Ashantha De Silva',
                'identity_card_no' => '199312345686',
                'phone_no' => '+94 77 901 2345',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 09',
                'bank_account_number' => '9012345678',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P010',
                'position_id' => $boyPosition->id,
                'promoter_name' => 'Nimal Rajapaksa',
                'identity_card_no' => '199412345687',
                'phone_no' => '+94 77 012 3456',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 10',
                'bank_account_number' => '0123456789',
                'status' => 'active'
            ],

            // Senior Promoters
            [
                'promoter_id' => 'P011',
                'position_id' => $seniorPosition->id,
                'promoter_name' => 'Priyanka Fernando',
                'identity_card_no' => '198512345688',
                'phone_no' => '+94 77 111 2222',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 11',
                'bank_account_number' => '1111222233',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P012',
                'position_id' => $seniorPosition->id,
                'promoter_name' => 'Ranjith Wijesinghe',
                'identity_card_no' => '198612345689',
                'phone_no' => '+94 77 222 3333',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 12',
                'bank_account_number' => '2222333344',
                'status' => 'active'
            ],

            // Team Leaders
            [
                'promoter_id' => 'P013',
                'position_id' => $teamLeaderPosition->id,
                'promoter_name' => 'Dilani Perera',
                'identity_card_no' => '198712345690',
                'phone_no' => '+94 77 333 4444',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 13',
                'bank_account_number' => '3333444455',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P014',
                'position_id' => $teamLeaderPosition->id,
                'promoter_name' => 'Sajith Wickramasinghe',
                'identity_card_no' => '198812345691',
                'phone_no' => '+94 77 444 5555',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 14',
                'bank_account_number' => '4444555566',
                'status' => 'active'
            ],

            // Brand Ambassadors
            [
                'promoter_id' => 'P015',
                'position_id' => $brandAmbassadorPosition->id,
                'promoter_name' => 'Priyanka Jayawardena',
                'identity_card_no' => '198912345692',
                'phone_no' => '+94 77 555 6666',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 15',
                'bank_account_number' => '5555666677',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P016',
                'position_id' => $brandAmbassadorPosition->id,
                'promoter_name' => 'Dr. Nimali Fernando',
                'identity_card_no' => '199012345693',
                'phone_no' => '+94 77 666 7777',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 16',
                'bank_account_number' => '6666777788',
                'status' => 'active'
            ],

            // Event Promoters
            [
                'promoter_id' => 'P017',
                'position_id' => $eventPosition->id,
                'promoter_name' => 'Dr. Ravi Perera',
                'identity_card_no' => '199112345694',
                'phone_no' => '+94 77 777 8888',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 17',
                'bank_account_number' => '7777888899',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P018',
                'position_id' => $eventPosition->id,
                'promoter_name' => 'Dr. Sanduni Silva',
                'identity_card_no' => '199212345695',
                'phone_no' => '+94 77 888 9999',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 18',
                'bank_account_number' => '8888999900',
                'status' => 'active'
            ],

            // Retail Promoters
            [
                'promoter_id' => 'P019',
                'position_id' => $retailPosition->id,
                'promoter_name' => 'Kumari Mendis',
                'identity_card_no' => '199312345696',
                'phone_no' => '+94 77 999 0000',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 19',
                'bank_account_number' => '9990000111',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P020',
                'position_id' => $retailPosition->id,
                'promoter_name' => 'Nishantha Rathnayake',
                'identity_card_no' => '199412345697',
                'phone_no' => '+94 77 000 1111',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 20',
                'bank_account_number' => '0001111222',
                'status' => 'active'
            ],

            // Field Promoters
            [
                'promoter_id' => 'P021',
                'position_id' => $fieldPosition->id,
                'promoter_name' => 'Samantha Gunawardena',
                'identity_card_no' => '199512345698',
                'phone_no' => '+94 77 111 3333',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 21',
                'bank_account_number' => '1113333444',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P022',
                'position_id' => $fieldPosition->id,
                'promoter_name' => 'Chaminda Weerasinghe',
                'identity_card_no' => '199612345699',
                'phone_no' => '+94 77 222 4444',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 22',
                'bank_account_number' => '2224444555',
                'status' => 'active'
            ],

            // Additional Girl Promoters
            [
                'promoter_id' => 'P023',
                'position_id' => $girlPosition->id,
                'promoter_name' => 'Anushka Senanayake',
                'identity_card_no' => '199712345700',
                'phone_no' => '+94 77 333 5555',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 23',
                'bank_account_number' => '3335555666',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P024',
                'position_id' => $girlPosition->id,
                'promoter_name' => 'Ishara Abeysekara',
                'identity_card_no' => '199812345701',
                'phone_no' => '+94 77 444 6666',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 24',
                'bank_account_number' => '4446666777',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P025',
                'position_id' => $girlPosition->id,
                'promoter_name' => 'Dilani Pathirana',
                'identity_card_no' => '199912345702',
                'phone_no' => '+94 77 555 7777',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 25',
                'bank_account_number' => '5557777888',
                'status' => 'active'
            ],

            // Additional Boy Promoters
            [
                'promoter_id' => 'P026',
                'position_id' => $boyPosition->id,
                'promoter_name' => 'Kasun Perera',
                'identity_card_no' => '200012345703',
                'phone_no' => '+94 77 666 8888',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 26',
                'bank_account_number' => '6668888999',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P027',
                'position_id' => $boyPosition->id,
                'promoter_name' => 'Nuwan Silva',
                'identity_card_no' => '200112345704',
                'phone_no' => '+94 77 777 9999',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 27',
                'bank_account_number' => '7779999000',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P028',
                'position_id' => $boyPosition->id,
                'promoter_name' => 'Tharindu Wickramasinghe',
                'identity_card_no' => '200212345705',
                'phone_no' => '+94 77 888 0000',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 28',
                'bank_account_number' => '8880000111',
                'status' => 'active'
            ],

            // Mixed Positions
            [
                'promoter_id' => 'P029',
                'position_id' => $seniorPosition->id,
                'promoter_name' => 'Madhushani Fernando',
                'identity_card_no' => '200312345706',
                'phone_no' => '+94 77 999 1111',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 29',
                'bank_account_number' => '9991111222',
                'status' => 'active'
            ],
            [
                'promoter_id' => 'P030',
                'position_id' => $teamLeaderPosition->id,
                'promoter_name' => 'Roshan Mendis',
                'identity_card_no' => '200412345707',
                'phone_no' => '+94 77 000 2222',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 30',
                'bank_account_number' => '0002222333',
                'status' => 'active'
            ]
        ];

        foreach ($promoters as $promoterData) {
            Promoter::firstOrCreate(
                ['promoter_id' => $promoterData['promoter_id']],
                $promoterData
            );
        }

        $this->command->info('Successfully seeded ' . count($promoters) . ' promoters!');
    }
}