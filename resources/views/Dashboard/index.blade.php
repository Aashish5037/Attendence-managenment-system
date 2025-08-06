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
                    <div class="flex flex-wrap lg:flex-nowrap gap-6 mb-8 items-stretch">

                        <x-info-box
                            label="Total Employees"
                            :value="$totalEmployees"
                            icon="fas fa-users"
                            color="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-700 dark:to-blue-900"
                            class="flex-shrink-0" />

                        <x-info-box
                            label="Today's Payroll"
                            :value="'Rs. ' . number_format($payrollToday, 2)"
                            icon="fas fa-money-bill-wave"
                            color="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-700 dark:to-purple-900"
                            class="flex-shrink-0" />

                        <x-info-box
                            label="Attendance / Absence Rate"
                            :value="
            ($totalEmployees > 0
                ? round(($presentCount / $totalEmployees) * 100, 1) . '% / ' . round(($absentCount / $totalEmployees) * 100, 1) . '%'
                : '0% / 0%')
        "

                            color="bg-gradient-to-r from-green-500 to-red-500 dark:from-green-700 dark:to-red-700"
                            class="flex-shrink-0" />

                    </div>


                    @if($hasData)
                    <!-- Charts Section -->
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 flex gap-4 flex-row">

                        <!-- Pie Chart -->
                        <div class="flex-1 min-w-0">
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

                        <!-- Line Chart -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-center font-bold mb-6 text-gray-900 dark:text-gray-100 text-lg">
                                <i class="fas fa-chart-line mr-2 text-green-500"></i>
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
                                    'rgba(34, 197, 94, 0.8)', // Green
                                    'rgba(239, 68, 68, 0.8)' // Red
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
                                        font: {
                                            size: 14
                                        },
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

                // Line Chart
                const lineCanvas = document.getElementById('weeklyAttendanceChart');
                if (lineCanvas) {
                    const lineCtx = lineCanvas.getContext('2d');
                    new Chart(lineCtx, {
                        type: 'line',
                        data: {
                            labels: dates,
                            datasets: [{
                                    label: 'Present',
                                    data: presentCounts,
                                    borderColor: 'rgba(34, 197, 94, 1)',
                                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 4,
                                    pointHoverRadius: 6
                                },
                                {
                                    label: 'Absent',
                                    data: absentCounts,
                                    borderColor: 'rgba(239, 68, 68, 1)',
                                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 4,
                                    pointHoverRadius: 6
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
                                        font: {
                                            size: 12
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(156, 163, 175, 0.3)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            size: 12
                                        }
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
            } else {
                console.log('Dashboard: No data available for charts');
            }
        });
    </script>

</x-app-layout>