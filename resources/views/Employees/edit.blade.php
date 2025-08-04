<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Edit Employee</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('employees.update', $employee->id) }}" class="space-y-4 bg-white dark:bg-gray-900 p-6 rounded shadow">
            @csrf
            @method('PUT')

            <!-- Readonly: Name -->
            <input
                type="text"
                name="employee_name"
                value="{{ $employee->employee_name }}"
                readonly
                class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100">

            <!-- Readonly: Email -->
            <input
                type="email"
                name="employee_email"
                value="{{ $employee->employee_email }}"
                readonly
                class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100">

            <!-- Readonly: Biometric ID -->
            <input
                type="text"
                name="employee_biometric_id"
                value="{{ $employee->employee_biometric_id }}"
                readonly
                class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100">
            <!-- Position -->
            <input
                type="text"
                name="employee_position"
                value="{{ $employee->employee_position }}"
                 class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100" 
                required>

            <!-- Hourly Pay -->
            <input
                type="text"
                name="employee_Hourly_pay"
                value="{{ $employee->employee_Hourly_pay }}"
                 class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100" 
                required>

            <!-- Overtime Pay -->
            <input
                type="text"
                name="employee_overtime_pay"
                value="{{ $employee->employee_overtime_pay }}"
                 class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100" 
                required>


            <div class="text-right">
               <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</x-app-layout>