<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(isset($error))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Error:</strong> {{ $error }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Info Boxes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <x-info-box 
                            label="Total Employees" 
                            :value="$totalEmployees" 
                            icon="fas fa-users" 
                            color="bg-gradient-to-r from-blue-500 to-blue-600" />
                        
                        <x-info-box 
                            label="Present Today" 
                            :value="$presentCount" 
                            icon="fas fa-check-circle" 
                            color="bg-gradient-to-r from-green-500 to-green-600" />
                        
                        <x-info-box 
                            label="Absent Today" 
                            :value="$absentCount" 
                            icon="fas fa-times-circle" 
                            color="bg-gradient-to-r from-red-500 to-red-600" />
                        
                        <x-info-box 
                            label="Today's Payroll" 
                            :value="'Rs. ' . number_format($payrollToday, 2)" 
                            icon="fas fa-money-bill-wave" 
                            color="bg-gradient-to-r from-purple-500 to-purple-600" />
                    </div>

                    @if($hasData)
                        <!-- Charts Section -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                            <!-- Pie Chart -->
                            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6">
                                <h3 class="text-center font-bold mb-6 text-gray-900 dark:text-gray-100 text-lg">
                                    <i class="fas fa-chart-pie mr-2 text-blue-500"></i>
                                    Today's Attendance
                                </h3>
                                <div class="relative h-64">
                                    <canvas id="attendancePieChart"></canvas>
                                </div>
                                <div class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                    Total: {{ $totalEmployees }} employees
                                </div>
                            </div>

                            <!-- Bar Chart -->
                            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6">
                                <h3 class="text-center font-bold mb-6 text-gray-900 dark:text-gray-100 text-lg">
                                    <i class="fas fa-chart-bar mr-2 text-green-500"></i>
                                    Last 7 Days Attendance
                                </h3>
                                <div class="relative h-64">
                                    <canvas id="weeklyAttendanceChart"></canvas>
                                </div>
                                <div class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                    Weekly attendance trend
                                </div>
                            </div>

                        </div>

                        <!-- Quick Stats Summary -->
                        <div class="mt-8 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Quick Summary
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="text-center">
                                    <div class="font-medium text-gray-700 dark:text-gray-300">Attendance Rate</div>
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ $totalEmployees > 0 ? round(($presentCount / $totalEmployees) * 100, 1) : 0 }}%
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="font-medium text-gray-700 dark:text-gray-300">Absence Rate</div>
                                    <div class="text-2xl font-bold text-red-600">
                                        {{ $totalEmployees > 0 ? round(($absentCount / $totalEmployees) * 100, 1) : 0 }}%
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="font-medium text-gray-700 dark:text-gray-300">Avg Daily Payroll</div>
                                    <div class="text-2xl font-bold text-purple-600">
                                        Rs. {{ $payrollToday > 0 ? number_format($payrollToday, 0) : '0' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <!-- No Data Message -->
                        <div class="text-center py-12">
                            <div class="text-gray-400 text-6xl mb-4">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100 mb-2">
                                No Data Available
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Add employees and attendance records to see dashboard statistics.
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Hidden data containers for JavaScript -->
    <div id="dashboard-data" style="display: none;"
         data-has-data="{{ $hasData ? 'true' : 'false' }}"
         data-present-count="{{ $presentCount }}"
         data-absent-count="{{ $absentCount }}"
         data-dates="{{ implode(',', $dates) }}"
         data-present-counts="{{ implode(',', $presentCounts) }}"
         data-absent-counts="{{ implode(',', $absentCounts) }}">
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get data from hidden div attributes (no PHP syntax in JS)
            const dataContainer = document.getElementById('dashboard-data');
            if (!dataContainer) {
                console.error('Dashboard data container not found');
                return;
            }

            const hasData = dataContainer.getAttribute('data-has-data') === 'true';
            const presentCount = parseInt(dataContainer.getAttribute('data-present-count')) || 0;
            const absentCount = parseInt(dataContainer.getAttribute('data-absent-count')) || 0;
            const dates = dataContainer.getAttribute('data-dates').split(',').filter(d => d.length > 0);
            const presentCounts = dataContainer.getAttribute('data-present-counts').split(',').map(n => parseInt(n) || 0);
            const absentCounts = dataContainer.getAttribute('data-absent-counts').split(',').map(n => parseInt(n) || 0);

            // Only create charts if we have data and elements exist
            if (hasData && (presentCount > 0 || absentCount > 0)) {
                
                // Pie Chart
                const pieCanvas = document.getElementById('attendancePieChart');
                if (pieCanvas) {
                    const pieCtx = pieCanvas.getContext('2d');
                    new Chart(pieCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Present', 'Absent'],
                            datasets: [{
                                data: [presentCount, absentCount],
                                backgroundColor: [
                                    'rgba(34, 197, 94, 0.8)',  // Green
                                    'rgba(239, 68, 68, 0.8)'   // Red
                                ],
                                borderColor: [
                                    'rgba(34, 197, 94, 1)',
                                    'rgba(239, 68, 68, 1)'
                                ],
                                borderWidth: 2,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        font: { size: 14 },
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const total = presentCount + absentCount;
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Bar Chart
            if (hasData && dates.length > 0) {
                const barCanvas = document.getElementById('weeklyAttendanceChart');
                if (barCanvas) {
                    const barCtx = barCanvas.getContext('2d');
                    new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: dates,
                            datasets: [
                                {
                                    label: 'Present',
                                    data: presentCounts,
                                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                    borderColor: 'rgba(34, 197, 94, 1)',
                                    borderWidth: 1,
                                    borderRadius: 4
                                },
                                {
                                    label: 'Absent',
                                    data: absentCounts,
                                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                    borderColor: 'rgba(239, 68, 68, 1)',
                                    borderWidth: 1,
                                    borderRadius: 4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { 
                                        stepSize: 1,
                                        font: { size: 12 }
                                    },
                                    grid: {
                                        color: 'rgba(156, 163, 175, 0.3)'
                                    }
                                },
                                x: {
                                    ticks: { 
                                        font: { size: 12 }
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                }
            }

            // Debug logging
            if (!hasData) {
                console.log('Dashboard: No data available for charts');
            }
        });
    </script>

</x-app-layout>