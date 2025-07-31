<aside class="w-64 h-screen bg-gray-800 text-gray-100 fixed">
    <div class="flex items-center justify-center h-16 border-b border-gray-700">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="h-10 w-auto text-white" />
        </a>
    </div>

    <nav class="mt-4">
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            Dashboard
        </x-sidebar-link>

        <x-sidebar-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
            Employees
        </x-sidebar-link>

        <x-sidebar-link :href="route('attendances.index')" :active="request()->routeIs('attendance.*')">
            Attendance
        </x-sidebar-link>

        <x-sidebar-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.*')">
            Payroll
        </x-sidebar-link>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-sidebar-link :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();">
                Logout
            </x-sidebar-link>
        </form>
    </nav>
</aside>
