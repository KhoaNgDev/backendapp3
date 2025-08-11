<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Repair>
 */
class RepairFactory extends Factory
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
            'user_id' => User::factory(),
            'vehicle_id' => Vehicle::factory(),
            'repair_date' => $this->faker->date(),
            'technician_note' => $this->faker->optional()->paragraph,
            'total_cost' => $this->faker->randomFloat(2, 100, 2000),
        ];
    }
}
