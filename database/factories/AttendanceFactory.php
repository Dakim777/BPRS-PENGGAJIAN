<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = \App\Models\Attendance::class;

    public function definition()
    {
        return [
            'employee_id' => null,
            'tanggal' => $this->faker->dateTimeThisYear()->format('Y-m-d'),
            'status' => 'hadir',
            'jam_masuk' => null,
            'jam_keluar' => null,
        ];
    }
}
