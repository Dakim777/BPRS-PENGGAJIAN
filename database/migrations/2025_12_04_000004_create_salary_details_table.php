<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_id')->constrained('salaries')->onDelete('cascade');
            $table->enum('jenis', ['tunjangan', 'potongan']);
            $table->string('keterangan')->nullable();
            $table->decimal('nominal', 15, 2)->default(0);
            $table->timestamps();
            $table->index(['salary_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_details');
    }
};
