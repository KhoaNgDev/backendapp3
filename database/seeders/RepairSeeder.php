<?php

namespace Database\Seeders;

use App\Models\Repair;
use App\Models\RepairFeedback;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RepairSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Toyota', 'Honda', 'Ford', 'Hyundai', 'Mazda'];
        $models = ['Vios', 'Civic', 'Ranger', 'Accent', 'CX-5'];
        $colors = ['Trắng', 'Đen', 'Xám', 'Đỏ', 'Xanh'];
        $plates = ['51K', '30A', '60K', '29C', '66A'];

        for ($u = 1; $u <= 5; $u++) {
            $name = fake()->name();
            $email = "user{$u}@example.test"; // Email giả định
            $phone = '09357693' . str_pad($u, 2, '0', STR_PAD_LEFT);
            $role = $u === 1 ? 'Admin' : 'User';

            $user = User::create([
                'name' => $name,
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => $email,
                'password' => Hash::make('password'),
                'phone' => $phone,
                'group_role' => $role,
                'is_active' => 'active',
                'status' => 'active',
                'created_at' => $created = Carbon::create(2025, 1, rand(1, 28)),
            ]);

            for ($i = 1; $i <= 2; $i++) {
                $plate_prefix = $plates[array_rand($plates)];
                $brand = $brands[array_rand($brands)];
                $model = $models[array_rand($models)];
                $color = $colors[array_rand($colors)];
                $year = rand(2018, 2022);

                $vehicle = Vehicle::create([
                    'user_id' => $user->id,
                    'plate_number' => $plate_prefix . rand(10000, 99999),
                    'brand' => $brand,
                    'model' => $model,
                    'color' => $color,
                    'year' => $year,
                    'note' => "Xe {$brand} {$model} màu {$color} của {$user->name}",
                    'maintenance_interval_months' => rand(4, 6),
                    'created_at' => $created->copy()->addDays(rand(1, 5)),
                ]);

                for ($j = 1; $j <= 2; $j++) {
                    $repairDate = Carbon::create(2025, rand(1, 8), rand(1, 28))->startOfDay();
                    $services = ['Thay nhớt', 'Bảo dưỡng động cơ', 'Căn chỉnh phanh', 'Thay lọc gió', 'Rửa xe toàn diện'];
                    $parts = ['Dầu nhớt', 'Lọc gió', 'Bugi', 'Phanh', 'Dây curoa'];
                    $notes = ['Xe chạy ổn định', 'Cần kiểm tra lại phanh sau', 'Động cơ hơi ồn', 'Đèn pha yếu', 'Vô lăng lệch nhẹ'];

                    $repair = Repair::create([
                        'user_id' => $user->id,
                        'vehicle_id' => $vehicle->id,
                        'repair_date' => $repairDate,
                        'services_performed' => $services[array_rand($services)],
                        'parts_replaced' => $parts[array_rand($parts)],
                        'technician_note' => $notes[array_rand($notes)],
                        'total_cost' => rand(800_000, 2_500_000),
                        'created_at' => $repairDate,
                    ]);

                    RepairFeedback::create([
                        'repair_id' => $repair->id,
                        'feedback' => fake()->sentence(),
                        'rating' => rand(3, 5),
                        'created_at' => $repairDate->copy()->addDays(1),
                    ]);

             
                }
            }
        }
    }
}