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
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow w-96">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Confirm Deletion</h2>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')

            <label for="password" class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Enter your password:</label>
            <input type="password" name="password" id="password"
                class="w-full p-2 border border-gray-300 rounded mb-4 dark:bg-gray-700 dark:text-white" required>

            <div class="flex justify-end">
                <button type="button" onclick="closeDeleteModal()" class="mr-2 px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(button) {
        let id = button.dataset.id; // Get ID from button
        let form = document.getElementById('deleteForm');
        let modal = document.getElementById('deleteModal');
        form.action = '/employees/' + id; // Set form action
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        let modal = document.getElementById('deleteModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>