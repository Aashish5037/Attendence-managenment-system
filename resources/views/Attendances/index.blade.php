<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight text-center">
            Attendance Records
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <div class="flex justify-between mb-3">
                    <div>
                        <a href="{{ route('attendances.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            + Add Attendance
                        </a>
                    </div>
                    <div>
                        <!-- Extra controls if needed -->
                    </div>
                </div>

                {{-- Render the DataTable --}}
                {!! $dataTable->table([
                    'class' => 'table table-striped table-bordered w-full text-sm text-left text-gray-700 dark:text-gray-200 dark:bg-gray-900'
                ], true) !!}
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* Your custom DataTables styling here (optional) */
    </style>
    @endpush

    @push('scripts')
        {{-- Include DataTables scripts --}}
        {!! $dataTable->scripts() !!}
    @endpush
</x-app-layout>
