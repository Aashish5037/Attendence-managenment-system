

<!-- @props(['label', 'value' => '', 'icon' => ''])

<div class="bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-4 rounded shadow w-60">
    <div class="flex items-center space-x-3">
        @if($icon)
            <i class="{{ $icon }} text-2xl text-blue-500"></i>
        @endif
        <div>
            <div class="text-sm">{{ $label }}</div>
            <div class="text-xl font-bold">{{ $value }}</div>
        </div>
    </div>
</div> -->
@props(['label', 'value', 'icon', 'color' => 'bg-gradient-to-r from-blue-500 to-blue-600'])

<div class="{{ $color }} text-gray-100 dark:text-white rounded-lg shadow-lg p-6 transform hover:scale-105 transition-all duration-200 hover:shadow-xl relative">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium opacity-90 mb-1">{{ $label }}</p>
            <p class="text-2xl font-bold truncate">{{ $value }}</p>
        </div>
        <div class="text-3xl opacity-80 ml-4">
            <i class="{{ $icon }}"></i>
        </div>
    </div>

    <!-- Optional pulse overlay (supports dark mode) -->
    <div class="absolute inset-0 bg-white dark:bg-black opacity-0 hover:opacity-10 rounded-lg transition-opacity duration-200 pointer-events-none"></div>
</div>

