<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\SalaryDetail;
use App\Models\Employee;
use App\Services\SalaryCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // <--- PENTING: Import Library PDF

class SalaryController extends Controller
{
    protected $calculator;

    public function __construct(SalaryCalculationService $calculator)
    {
        $this->calculator = $calculator;
    }

    public function index(Request $request)
    {
        $query = Salary::with('employee');

        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        if ($request->filled('nip')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('nip', $request->nip)->orWhere('nama', 'like', '%'.$request->nip.'%');
            });
        }

        $salaries = $query->orderBy('periode', 'desc')->paginate(20);
        $employees = Employee::orderBy('nama', 'asc')->get();

        return view('salaries.index', compact('salaries', 'employees'));
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'periode' => 'required|date_format:Y-m',
        ]);

        $employee = Employee::findOrFail($data['employee_id']);
        $result = $this->calculator->calculateForEmployee($employee, $data['periode']);

        return view('salaries.calculate', compact('result', 'employee'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'periode' => 'required|date_format:Y-m',
        ]);

        return DB::transaction(function () use ($data) {
            $employee = Employee::findOrFail($data['employee_id']);
            
            $calc = $this->calculator->calculateForEmployee($employee, $data['periode']);

            $salary = Salary::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'periode' => $data['periode']
                ],
                [
                    'gaji_pokok' => $calc['gaji_pokok'],
                    'total_tunjangan' => $calc['total_tunjangan'],
                    'total_potongan' => $calc['total_potongan'],
                    'gaji_bersih' => $calc['gaji_bersih'],
                    'status_pembayaran' => 'pending', 
                ]
            );

            $salary->details()->delete();

            foreach ($calc['details_list'] as $detail) {
                $salary->details()->create([
                    'jenis' => $detail['jenis'],
                    'keterangan' => $detail['keterangan'],
                    'nominal' => $detail['nominal'],
                ]);
            }

            return redirect()->route('salaries.index')
                ->with('success', 'Perhitungan Gaji berhasil disimpan beserta rinciannya.');
        });
    }

    public function updateStatus(Request $request, Salary $salary)
    {
        $data = $request->validate([
            'status_pembayaran' => 'required|in:pending,paid,unpaid',
        ]);

        $updateData = ['status_pembayaran' => $data['status_pembayaran']];

        if ($data['status_pembayaran'] === 'paid') {
            $updateData['tanggal_pembayaran'] = now();
        } else {
            $updateData['tanggal_pembayaran'] = null;
        }

        $salary->update($updateData);

        return redirect()->back()->with('success', 'Status pembayaran diperbarui.');
    }

    public function report(Request $request)
    {
        $salaries = Salary::with(['employee', 'details'])
            ->when($request->filled('periode'), fn($q) => $q->where('periode', $request->periode))
            ->orderBy('periode', 'desc')
            ->get();

        return view('salaries.report', compact('salaries'));
    }

    
    public function downloadPdf($id)
    {
        $salary = Salary::with(['employee', 'details'])->findOrFail($id);
        
        $pdf = Pdf::loadView('salaries.slip_pdf', compact('salary'));
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'Slip-Gaji-' . $salary->employee->nip . '-' . $salary->periode . '.pdf';
        return $pdf->download($fileName);
    }
}