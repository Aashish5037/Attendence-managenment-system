<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable(); // nullable
            $table->date('period_date');
            $table->decimal('overtime_pay', 12, 2)->nullable();
            $table->decimal('net_pay', 12, 2)->nullable();
            $table->timestamps();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('set null')  // Set null on employee delete
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
