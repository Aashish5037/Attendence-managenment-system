<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Edit Employee</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('employees.update', $employee->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <input type="text" name="name" value="{{ $employee->name }}" placeholder="Full Name" class="w-full p-2 border rounded" required>

            <input type="email" name="email" value="{{ $employee->email }}" placeholder="Email" class="w-full p-2 border rounded" required>

            <input type="text" name="position" value="{{ $employee->position }}" placeholder="Position" class="w-full p-2 border rounded" required>

            <input type="number" name="hourly_pay" value="{{ $employee->hourly_pay }}" step="0.01" class="w-full p-2 border rounded" required>

            <input type="number" name="overtime_pay" value="{{ $employee->overtime_pay }}" step="0.01" class="w-full p-2 border rounded" required>

            <input type="text" name="biometric_id" value="{{ $employee->biometric_id }}" class="w-full p-2 border rounded" required>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update Employee</button>
        </form>
    </div>
</x-app-layout>
