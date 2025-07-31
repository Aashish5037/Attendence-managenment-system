<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Edit Employee</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('employees.update', $employee->id) }}" class="space-y-4 bg-white dark:bg-gray-900 p-6 rounded shadow">
            @csrf
            @method('PUT')

            <input type="text" name="name" value="{{ $employee->name }}" placeholder="Full Name" readonly 
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100">

            <input type="email" name="email" value="{{ $employee->email }}" placeholder="Email" readonly 
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100">

            <input type="text" name="position" value="{{ $employee->position }}" placeholder="Position" 
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100" required>

            <input type="number" name="hourly_pay" value="{{ $employee->hourly_pay }}" step="0.01" 
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100" required>

            <input type="number" name="overtime_pay" value="{{ $employee->overtime_pay }}" step="0.01" 
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100" required>

            <input type="text" name="biometric_id" value="{{ $employee->biometric_id }}" readonly 
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100">

            <div class="text-right">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
