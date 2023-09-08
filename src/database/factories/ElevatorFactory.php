<?php

namespace Database\Factories;

use App\Models\Elevator;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElevatorFactory extends Factory
{
    protected $model = Elevator::class;

    public function definition()
    {
        $validCharacters = ['A', 'B', 'C', 'D', 'E', 'F', 'G']; // Add more characters as needed

        return [
            'name' => $this->faker->randomElement($validCharacters),
            'current_floor' => $this->faker->numberBetween(-10, 20),
            'target_floor' => $this->faker->numberBetween(-10, 20),
            'direction' => $this->faker->randomElement(['up', 'down']),
        ];
    }
}
