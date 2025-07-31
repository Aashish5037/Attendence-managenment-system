<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-wrap gap-4 justify-start">
                        <x-info-box label="Employees (Total)" :value="$totalEmployees" icon="fas fa-user-tie" />
                        <x-info-box label="Present Today" :value="$presentCount" icon="fas fa-check-circle" />
                        <x-info-box label="Absent Today" :value="$absentCount" icon="fas fa-times-circle" />
                        <x-info-box label="Today's Payroll" :value="'Rs. ' . number_format($payrollToday, 2)" icon="fas fa-money-bill-wave" />
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>