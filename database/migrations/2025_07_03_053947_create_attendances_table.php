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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Make employee_id nullable so if employee is deleted, we can keep the record
            $table->unsignedBigInteger('employee_id')->nullable();

            // Device ID stays as is
            $table->string('device_id');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->decimal('total_hours', 5, 2)->nullable()->default(0);
            $table->integer('overtime_minutes')->nullable()->default(0);

            // Historical snapshot columns so we keep employee details for the record
            $table->string('employee_name')->nullable();
            $table->string('employee_position')->nullable();
            $table->decimal('employee_salary', 10, 2)->nullable();

            $table->timestamps();

            // Foreign key now SETS employee_id to null instead of deleting the row
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
