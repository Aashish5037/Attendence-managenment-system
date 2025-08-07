<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title> 

    <!-- DataTables Core CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Tailwind and App Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-gray-900 dark:bg-gray-900 dark:text-white font-sans">

    <div class=" min-h-screen flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 border-r">
            <div class="p-4 font-bold text-xl border-b text-gray-800 dark:text-gray-200">AMS</div>
            <nav class="p-4 space-y-2 text-gray-700 dark:text-gray-300">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Dashboard</a>
                <a href="{{ route('attendances.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Attendance</a>
                <a href="{{ route('employees.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Employees</a>
                <a href="{{ route('payrolls.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Payrolls</a>
                <!-- <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">Settings</a> -->

               <form method="POST" action="{{ route('logout') }}" id="logout-form">
    @csrf
    <x-responsive-nav-link href="{{ route('logout') }}"
        onclick="event.preventDefault(); 
            if (confirm('Are you sure you want to log out?')) {
                document.getElementById('logout-form').submit();
            }">
        {{ __('Log Out') }}
    </x-responsive-nav-link>
</form>

            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            @include('layouts.navigation')


            @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endisset

            <main class="p-4">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- ✅ Scripts in the correct order -->

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables Core -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>


    <!-- Export Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Yajra Scripts & Others -->
    @stack('scripts')

</body>

</html>