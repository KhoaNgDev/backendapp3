<?php

namespace Database\Factories;

use App\Models\Repair;
use App\Models\RepairPart;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepairPart>
 */
class RepairPartFactory extends Factory
{
    use HasFactory;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     protected $model = RepairPart::class;

    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $unit_price = $this->faker->randomFloat(2, 20, 200);
        return [
            'repair_id' => Repair::factory(),
            'part_name' => $this->faker->word,
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'total_price' => $quantity * $unit_price,
        ];
    }
}
