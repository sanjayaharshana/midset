<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Allowance;

class AllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allowances = [
            [
                'name' => 'Food Allowance',
                'description' => 'Daily food allowance for promoters'
            ],
            [
                'name' => 'Transport Allowance',
                'description' => 'Transportation allowance for commuting'
            ],
            [
                'name' => 'Accommodation Allowance',
                'description' => 'Accommodation allowance for outstation work'
            ],
            [
                'name' => 'Medical Allowance',
                'description' => 'Medical expenses allowance'
            ],
            [
                'name' => 'Communication Allowance',
                'description' => 'Mobile phone and communication expenses'
            ],
            [
                'name' => 'Uniform Allowance',
                'description' => 'Uniform and clothing allowance'
            ],
            [
                'name' => 'Overtime Allowance',
                'description' => 'Additional payment for overtime work'
            ],
            [
                'name' => 'Performance Bonus',
                'description' => 'Bonus based on performance metrics'
            ]
        ];

        foreach ($allowances as $allowance) {
            Allowance::create($allowance);
        }
    }
}
