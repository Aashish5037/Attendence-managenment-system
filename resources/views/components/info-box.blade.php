<!-- <div class="w-40 p-4 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg shadow flex flex-col items-center">
    <div class="text-3xl mb-2">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="text-sm font-semibold text-center">
        {{ $label }}
    </div>
</div> -->

@props(['label', 'value' => '', 'icon' => ''])

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
</div>

