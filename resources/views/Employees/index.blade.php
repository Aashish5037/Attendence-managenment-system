<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight text-center">
            Employees
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <div class="flex justify-between mb-3">
                    <div>
                        <a href="{{ route('employees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            + Add Employee
                        </a>
                    </div>
                    <div>
                        <!-- Extra controls if needed -->
                    </div>
                </div>

                {!! $dataTable->table(['class' => 'table table-striped table-bordered w-full text-sm text-left text-gray-700 dark:text-gray-200 dark:bg-gray-900'], true) !!}
            </div>
        </div>
    </div>

    @push('scripts')
        {!! $dataTable->scripts() !!}
    @endpush
</x-app-layout>
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow w-96">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Confirm Deletion</h2>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <label class="block mb-2 text-sm">Enter your password:</label>
            <input type="password" name="password" class="w-full mb-4 p-2 border rounded" required>
            <div class="flex justify-end">
                <button type="button" onclick="closeDeleteModal()" class="mr-2 px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(id) {
        const form = document.getElementById('deleteForm');
        form.action = '/employees/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>

