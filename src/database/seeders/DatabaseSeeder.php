<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
        public function run(): void
    {
        $elevatorsData = [
            [
                'name' => 'A',
                'current_floor' => 5,
                'target_floor' => 9,
                'direction' => 'up',
            ],
            [
                'name' => 'B',
                'current_floor' => -1,
                'target_floor' => -4,
                'direction' => 'down',
            ],
            [
                'name' => 'C',
                'current_floor' => 14,
                'target_floor' => 0,
                'direction' => 'down',
            ],
            [
                'name' => 'D',
                'current_floor' => 1,
                'target_floor' => 5,
                'direction' => 'up',
            ],
        ];

        foreach ($elevatorsData as $elevatorData) {
            \App\Models\Elevator::factory()->create($elevatorData);
        }
    }

}
