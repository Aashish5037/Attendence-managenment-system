<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Add New Employee</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('employees.store') }}" class="space-y-4">
            @csrf

            <input type="text" name="name" placeholder="Full Name" class="w-full p-2 border rounded" required>

            <input type="email" name="email" placeholder="Email" class="w-full p-2 border rounded" required>

            <input type="text" name="position" placeholder="Position" class="w-full p-2 border rounded" required>

            <input type="number" name="hourly_pay" placeholder="Hourly Pay" step="0.01" class="w-full p-2 border rounded" required>

            <input type="number" name="overtime_pay" placeholder="Overtime Pay" step="0.01" class="w-full p-2 border rounded" required>

            <input type="text" name="biometric_id" placeholder="Biometric ID" class="w-full p-2 border rounded" required>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Employee</button>
        </form>
    </div>
</x-app-layout>
