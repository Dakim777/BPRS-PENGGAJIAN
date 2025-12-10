<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin Default (Agar Anda bisa login nanti)
        // Cek apakah class User ada (bawaan Laravel)
        if (class_exists(User::class)) {
            User::factory()->create([
                'name' => 'Admin Sistem',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), // Password untuk login
            ]);
        }

        // 2. Buat 10 Data Karyawan Dummy
        // Menggunakan EmployeeFactory yang Anda upload
        $employees = Employee::factory()->count(10)->create();

        // 3. Buat Data Absensi Dummy untuk setiap karyawan
        // Menggunakan AttendanceFactory
        foreach ($employees as $employee) {
            Attendance::factory()->count(5)->create([
                'employee_id' => $employee->id,
                'status' => 'hadir', // Default hadir
            ]);
            
            // Tambahkan 1-2 data absen/sakit agar data variatif
            Attendance::factory()->create([
                'employee_id' => $employee->id,
                'status' => 'sakit',
            ]);
        }
    }
}