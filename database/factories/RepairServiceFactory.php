<?php

namespace Database\Factories;

use App\Models\Repair;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepairService>
 */
class RepairServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'repair_id' => Repair::factory(),
            'service_name' => $this->faker->word,
            'service_cost' => $this->faker->randomFloat(2, 50, 500),
            'description' => $this->faker->optional()->sentence,
        ];
    }
}
