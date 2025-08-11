<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    use HasFactory;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected static $plateCounter = 88888;

    public function definition(): array
    {
        $plateNumber = '51K' . static::$plateCounter++;

        return [
            'user_id' => User::factory(),
            'plate_number' => $plateNumber,
            'brand' => $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'Yamaha']),
            'model' => $this->faker->bothify('Model-###'),
            'color' => $this->faker->safeColorName(),
            'year' => $this->faker->year(),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
