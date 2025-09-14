<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coordinator;

class CoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coordinators = [
            // Senior Coordinators
            [
                'coordinator_id' => 'C001',
                'coordinator_name' => 'Mr. Nimal Rajapaksa',
                'nic_no' => '198512345678',
                'phone_no' => '+94 77 111 1111',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 01',
                'account_number' => '1111111111',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C002',
                'coordinator_name' => 'Ms. Priyanka Fernando',
                'nic_no' => '198612345679',
                'phone_no' => '+94 77 222 2222',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 02',
                'account_number' => '2222222222',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C003',
                'coordinator_name' => 'Mr. Ravi Jayasuriya',
                'nic_no' => '198712345680',
                'phone_no' => '+94 77 333 3333',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 03',
                'account_number' => '3333333333',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C004',
                'coordinator_name' => 'Ms. Sanduni Perera',
                'nic_no' => '198812345681',
                'phone_no' => '+94 77 444 4444',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 04',
                'account_number' => '4444444444',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C005',
                'coordinator_name' => 'Mr. Dinesh Karunaratne',
                'nic_no' => '198912345682',
                'phone_no' => '+94 77 555 5555',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 05',
                'account_number' => '5555555555',
                'status' => 'active'
            ],

            // Regional Coordinators
            [
                'coordinator_id' => 'C006',
                'coordinator_name' => 'Ms. Tharanga Gunasekara',
                'nic_no' => '199012345683',
                'phone_no' => '+94 77 666 6666',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 06',
                'account_number' => '6666666666',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C007',
                'coordinator_name' => 'Mr. Ashantha De Silva',
                'nic_no' => '199112345684',
                'phone_no' => '+94 77 777 7777',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 07',
                'account_number' => '7777777777',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C008',
                'coordinator_name' => 'Ms. Nimali Silva',
                'nic_no' => '199212345685',
                'phone_no' => '+94 77 888 8888',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 08',
                'account_number' => '8888888888',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C009',
                'coordinator_name' => 'Mr. Chamari Wickramasinghe',
                'nic_no' => '199312345686',
                'phone_no' => '+94 77 999 9999',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 09',
                'account_number' => '9999999999',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C010',
                'coordinator_name' => 'Ms. Dilani Perera',
                'nic_no' => '199412345687',
                'phone_no' => '+94 77 000 0000',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 10',
                'account_number' => '0000000000',
                'status' => 'active'
            ],

            // Field Coordinators
            [
                'coordinator_id' => 'C011',
                'coordinator_name' => 'Mr. Ranjith Wijesinghe',
                'nic_no' => '199512345688',
                'phone_no' => '+94 77 111 2222',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 11',
                'account_number' => '1111222233',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C012',
                'coordinator_name' => 'Ms. Sajith Wickramasinghe',
                'nic_no' => '199612345689',
                'phone_no' => '+94 77 222 3333',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 12',
                'account_number' => '2222333344',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C013',
                'coordinator_name' => 'Mr. Priyanka Jayawardena',
                'nic_no' => '199712345690',
                'phone_no' => '+94 77 333 4444',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 13',
                'account_number' => '3333444455',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C014',
                'coordinator_name' => 'Ms. Dr. Nimali Fernando',
                'nic_no' => '199812345691',
                'phone_no' => '+94 77 444 5555',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 14',
                'account_number' => '4444555566',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C015',
                'coordinator_name' => 'Mr. Dr. Ravi Perera',
                'nic_no' => '199912345692',
                'phone_no' => '+94 77 555 6666',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 15',
                'account_number' => '5555666677',
                'status' => 'active'
            ],

            // Event Coordinators
            [
                'coordinator_id' => 'C016',
                'coordinator_name' => 'Ms. Dr. Sanduni Silva',
                'nic_no' => '200012345693',
                'phone_no' => '+94 77 666 7777',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 16',
                'account_number' => '6666777788',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C017',
                'coordinator_name' => 'Mr. Kumari Mendis',
                'nic_no' => '200112345694',
                'phone_no' => '+94 77 777 8888',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 17',
                'account_number' => '7777888899',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C018',
                'coordinator_name' => 'Ms. Nishantha Rathnayake',
                'nic_no' => '200212345695',
                'phone_no' => '+94 77 888 9999',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 18',
                'account_number' => '8888999900',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C019',
                'coordinator_name' => 'Mr. Samantha Gunawardena',
                'nic_no' => '200312345696',
                'phone_no' => '+94 77 999 0000',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 19',
                'account_number' => '9990000111',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C020',
                'coordinator_name' => 'Ms. Chaminda Weerasinghe',
                'nic_no' => '200412345697',
                'phone_no' => '+94 77 000 1111',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 20',
                'account_number' => '0001111222',
                'status' => 'active'
            ],

            // Junior Coordinators
            [
                'coordinator_id' => 'C021',
                'coordinator_name' => 'Mr. Anushka Senanayake',
                'nic_no' => '200512345698',
                'phone_no' => '+94 77 111 3333',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 21',
                'account_number' => '1113333444',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C022',
                'coordinator_name' => 'Ms. Ishara Abeysekara',
                'nic_no' => '200612345699',
                'phone_no' => '+94 77 222 4444',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 22',
                'account_number' => '2224444555',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C023',
                'coordinator_name' => 'Mr. Dilani Pathirana',
                'nic_no' => '200712345700',
                'phone_no' => '+94 77 333 5555',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 23',
                'account_number' => '3335555666',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C024',
                'coordinator_name' => 'Ms. Kasun Perera',
                'nic_no' => '200812345701',
                'phone_no' => '+94 77 444 6666',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 24',
                'account_number' => '4446666777',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C025',
                'coordinator_name' => 'Mr. Nuwan Silva',
                'nic_no' => '200912345702',
                'phone_no' => '+94 77 555 7777',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 25',
                'account_number' => '5557777888',
                'status' => 'active'
            ],

            // Specialized Coordinators
            [
                'coordinator_id' => 'C026',
                'coordinator_name' => 'Ms. Tharindu Wickramasinghe',
                'nic_no' => '201012345703',
                'phone_no' => '+94 77 666 8888',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 26',
                'account_number' => '6668888999',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C027',
                'coordinator_name' => 'Mr. Madhushani Fernando',
                'nic_no' => '201112345704',
                'phone_no' => '+94 77 777 9999',
                'bank_name' => 'Sampath Bank',
                'bank_branch_name' => 'Colombo 27',
                'account_number' => '7779999000',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C028',
                'coordinator_name' => 'Ms. Roshan Mendis',
                'nic_no' => '201212345705',
                'phone_no' => '+94 77 888 0000',
                'bank_name' => 'Hatton National Bank',
                'bank_branch_name' => 'Colombo 28',
                'account_number' => '8880000111',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C029',
                'coordinator_name' => 'Mr. Test Coordinator',
                'nic_no' => '201312345706',
                'phone_no' => '+94 77 999 1111',
                'bank_name' => 'Commercial Bank of Ceylon',
                'bank_branch_name' => 'Colombo 29',
                'account_number' => '9991111222',
                'status' => 'active'
            ],
            [
                'coordinator_id' => 'C030',
                'coordinator_name' => 'Ms. Senior Coordinator',
                'nic_no' => '201412345707',
                'phone_no' => '+94 77 000 2222',
                'bank_name' => 'People\'s Bank',
                'bank_branch_name' => 'Colombo 30',
                'account_number' => '0002222333',
                'status' => 'active'
            ]
        ];

        foreach ($coordinators as $coordinatorData) {
            Coordinator::firstOrCreate(
                ['coordinator_id' => $coordinatorData['coordinator_id']],
                $coordinatorData
            );
        }

        $this->command->info('Successfully seeded ' . count($coordinators) . ' coordinators!');
    }
}