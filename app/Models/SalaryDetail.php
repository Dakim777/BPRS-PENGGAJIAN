<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_id', 'jenis', 'keterangan', 'nominal'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }
}
