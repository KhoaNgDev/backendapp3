<?php

namespace Database\Factories;

use App\Models\Repair;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepairFeedback>
 */
class RepairFeedbackFactory extends Factory
{
    use HasFactory;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'repair_id' => Repair::factory(),
            'feedback' => $this->faker->optional()->sentence,
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
