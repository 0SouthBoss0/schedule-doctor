<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'Иванов Иван Иванович',
                'specialization' => 'Терапевт',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Петрова Анна Сергеевна',
                'specialization' => 'Хирург',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Сидоров Алексей Владимирович',
                'specialization' => 'Кардиолог',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Кузнецова Елена Дмитриевна',
                'specialization' => 'Невролог',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Васильев Дмитрий Олегович',
                'specialization' => 'Офтальмолог',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Doctor::insert($doctors);
    }
}