@props(['active' => false])

@php
$classes = $active
    ? 'block px-4 py-2 bg-gray-900 text-white font-semibold'
    : 'block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
