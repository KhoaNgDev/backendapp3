<?php

namespace Database\Factories;

use App\Models\MaintenanceSchedules;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Testing\Fluent\Concerns\Has;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MaintenanceSchedulesFactory extends Factory
{   
    use HasFactory;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MaintenanceSchedules::class;

    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'next_maintenance_date' => $this->faker->dateTimeBetween('+1 week', '+1 year'),
            'reminder_sent' => $this->faker->boolean,
            'note' => $this->faker->optional()->sentence,
            'notified_at' => $this->faker->optional()->dateTime,
        ];
    }
}
