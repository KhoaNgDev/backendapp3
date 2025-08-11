<?php

namespace Database\Seeders;

use App\Models\MaintenanceSchedules;
use App\Models\Repair;
use App\Models\RepairFeedback;
use App\Models\RepairPart;
use App\Models\RepairService;
use App\Models\User;
use App\Models\Vehicle;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RepairSeeder::class);
    }
}
