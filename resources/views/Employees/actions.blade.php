<!-- <div class="flex gap-x-6">
    <a href="{{ route('employees.attendance', $employee->id) }}" title="View Attendance">
        <svg class="w-5 h-5 text-blue-600 hover:text-blue-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
        </svg>
    </a>

    <a href="{{ route('employees.edit', $employee->id) }}" title="Edit">
        <svg class="w-5 h-5 text-yellow-600 hover:text-yellow-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15.232 5.232l3.536 3.536M9 13l6-6 3.536 3.536L12 16.5H9v-3.5z"/>
        </svg>
    </a>

    <button data-id="{{ $employee->id }}" onclick="openDeleteModal(this)" title="Delete">
        <svg class="w-5 h-5 text-red-600 hover:text-red-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m4 0H5"/>
        </svg>
    </button>
</div> -->

<div class="flex gap-x-6">
    <!-- View Attendance -->
    <a href="{{ route('employees.attendance', $employee->id) }}" title="View Attendance">
        <svg class="w-5 h-5 text-blue-600 hover:text-blue-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
        </svg>
    </a>

    <!-- Edit -->
    <a href="{{ route('employees.edit', $employee->id) }}" title="Edit">
        <svg class="w-5 h-5 text-yellow-600 hover:text-yellow-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15.232 5.232l3.536 3.536M9 13l6-6 3.536 3.536L12 16.5H9v-3.5z"/>
        </svg>
    </a>

    <!-- Delete -->
    <button data-id="{{ $employee->id }}" onclick="openDeleteModal(this)" title="Delete">
        <svg class="w-5 h-5 text-red-600 hover:text-red-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m4 0H5"/>
        </svg>
    </button>
</div>

