<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight text-center">
        Attendance Record of {{ $employee}}
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="overflow-x-auto">
                <table id="attendanceTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Check In</th>
                            <th class="px-4 py-2 text-left">Check Out</th>
                            <th class="px-4 py-2 text-left">Total Hours</th>
                            <th class="px-4 py-2 text-left">Overtime(min)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td class="px-4 py-2">{{ $attendance->date }}</td>
                                <td class="px-4 py-2">{{ $attendance->check_in }}</td>
                                <td class="px-4 py-2">{{ $attendance->check_out }}</td>
                                <td class="px-4 py-2">{{ $attendance->total_hours }}</td>
                                <td class="px-4 py-2">{{ $attendance->overtime_minutes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#attendanceTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                    responsive: true
                });
            });
        </script>
    @endpush
</x-app-layout>
