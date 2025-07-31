<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Edit Attendance
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('attendances.update', $attendance->id) }}">
            @csrf
            @method('PUT')

            <!-- Employee Name (readonly) -->
            <div class="mb-4">
                <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Employee Name</label>
                <input type="text" value="{{ $attendance->employee->employee_name ?? 'N/A' }}" readonly
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-3 py-2" />
            </div>

            <!-- Date (readonly) -->
            <div class="mb-4">
                <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                <input type="date" value="{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}" readonly
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-3 py-2" />
            </div>

            <!-- Check In (editable) -->
            <div class="mb-4">
                <label for="check_in" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Check In</label>
                <input type="datetime-local" id="check_in" name="check_in"
                    value="{{ old('check_in', \Carbon\Carbon::parse($attendance->check_in)->format('Y-m-d\TH:i')) }}"
                    required
                    class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2" />
            </div>

            <!-- Check Out (editable) -->
            <div class="mb-6">
                <label for="check_out" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">Check Out</label>
                <input type="datetime-local" id="check_out" name="check_out"
                    value="{{ old('check_out', \Carbon\Carbon::parse($attendance->check_out)->format('Y-m-d\TH:i')) }}"
                    required
                    class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2" />
            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Update Attendance
            </button>
        </form>
    </div>
</x-app-layout>
