<?php

namespace Database\Seeders;

use App\Models\MaintenanceSchedules;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Repair;
use App\Models\RepairFeedback;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $usersData = [
            ['email' => 'nganhkhoa.becloud@gmail.com', 'group_role' => 'Admin', 'phone' => '0935769333'],
            ['email' => 'nguyen.anh.khoa.rcvn2012@gmail.com', 'group_role' => 'User', 'phone' => '0935769311'],
            ['email' => 'khoaanguyen36@gmail.com', 'group_role' => 'User', 'phone' => '0935769312'],
            ['email' => 'kanenguyen.resume.dev@gmail.com', 'group_role' => 'User', 'phone' => '0935769313'],
            ['email' => 'mrkhoabin@gmail.com', 'group_role' => 'User', 'phone' => '0935769314'],
        ];

        $brands = ['Toyota', 'Honda', 'Ford', 'Hyundai', 'Mazda'];
        $models = ['Vios', 'Civic', 'Ranger', 'Accent', 'CX-5'];
        $colors = ['Trắng', 'Đen', 'Xám', 'Đỏ', 'Xanh'];
        $plates = ['51K', '30A', '60K', '29C', '66A'];

        foreach ($usersData as $index => $data) {
            $user = User::create([
                'name' => fake()->name(),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'phone' => $data['phone'],
                'group_role' => $data['group_role'],
                'is_active' => 'active',
                'status' => 'active',
            ]);

            for ($i = 1; $i <= 2; $i++) {
                $plate_prefix = $plates[array_rand($plates)];
                $brand = $brands[array_rand($brands)];
                $model = $models[array_rand($models)];
                $color = $colors[array_rand($colors)];
                $year = rand(2015, 2022);

                $vehicle = Vehicle::create([
                    'user_id' => $user->id,
                    'plate_number' => $plate_prefix . rand(10000, 99999),
                    'brand' => $brand,
                    'model' => $model,
                    'color' => $color,
                    'year' => $year,
                    'note' => "Xe {$brand} {$model} màu {$color} của {$user->name}",
                    'maintenance_interval_months' => rand(4, 6),
                ]);

                for ($j = 1; $j <= 2; $j++) {
                    $repairDate = Carbon::now()->subMonths($j * rand(1, 3));
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
                        'total_cost' => rand(800000, 2500000),
                    ]);

                    RepairFeedback::create([
                        'repair_id' => $repair->id,
                        'feedback' => fake()->sentence(),
                        'rating' => rand(3, 5),
                    ]);
                }
            }
        }
    }
}
