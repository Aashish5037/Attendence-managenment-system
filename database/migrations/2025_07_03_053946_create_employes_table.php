<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('employee_biometric_id')->unique();
            $table->string('employee_name');
            $table->string('employee_email')->unique();
            $table->string('employee_position');
            $table->decimal('employee_Hourly_pay', 10, 2)->nullable();
            $table->decimal('employee_overtime_pay', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add soft deletes column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
