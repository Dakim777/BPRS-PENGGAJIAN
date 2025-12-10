<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Employee;
use App\Models\Attendance;
use App\Services\SalaryCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SalaryCalculationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_simple_salary()
    {
        $employee = Employee::factory()->create([
            'gaji_pokok' => 2200000,
            'tunjangan' => 200000,
            'potongan' => 50000,
        ]);

        // create 20 hadir records
        for ($i = 1; $i <= 20; $i++) {
            Attendance::create(['employee_id' => $employee->id, 'tanggal' => now()->startOfMonth()->addDays($i-1), 'status' => 'hadir']);
        }

        $service = new SalaryCalculationService();
        $result = $service->calculateForEmployee($employee, now()->format('Y-m'));

        $this->assertArrayHasKey('gaji_bersih', $result);
        $this->assertGreaterThan(0, $result['gaji_bersih']);
    }

    public function test_calculate_with_many_absences_reduces_salary()
    {
        $employee = Employee::factory()->create([
            'gaji_pokok' => 2200000,
            'tunjangan' => 200000,
            'potongan' => 0,
        ]);

        // create 10 hadir and 10 absen
        for ($i = 1; $i <= 10; $i++) {
            Attendance::create(['employee_id' => $employee->id, 'tanggal' => now()->startOfMonth()->addDays($i-1), 'status' => 'hadir']);
        }
        for ($i = 11; $i <= 20; $i++) {
            Attendance::create(['employee_id' => $employee->id, 'tanggal' => now()->startOfMonth()->addDays($i-1), 'status' => 'absen']);
        }

        $service = new SalaryCalculationService();
        $result = $service->calculateForEmployee($employee, now()->format('Y-m'));

        // absent should reduce the net salary compared to base
        $this->assertLessThan($employee->gaji_pokok + $employee->tunjangan, $result['gaji_bersih']);
    }
}
