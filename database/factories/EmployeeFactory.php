<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    protected $model = \App\Models\Employee::class;

    public function definition()
    {
        return [
            'nip' => $this->faker->unique()->numerify('EMP####'),
            'nama' => $this->faker->name,
            'jabatan' => $this->faker->jobTitle,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'status_kehadiran' => 'aktif',
            'gaji_pokok' => 2000000,
            'tunjangan' => 200000,
            'potongan' => 0,
        ];
    }
}
