<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('periode'); // format YYYY-MM
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('total_tunjangan', 15, 2)->default(0);
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->decimal('gaji_bersih', 15, 2)->default(0);
            // UPDATE: Mendukung status 'unpaid'
            $table->enum('status_pembayaran', ['pending', 'paid', 'unpaid'])->default('pending');
            $table->date('tanggal_pembayaran')->nullable();
            $table->timestamps();
            
            // Mencegah duplikasi gaji untuk karyawan yang sama di periode yang sama
            $table->unique(['employee_id', 'periode']);
            $table->index(['periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};