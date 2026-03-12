@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 border-b-2 border-orange-600 dark:border-orange-500 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none transition duration-200 ease-in-out'
            : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-900 dark:focus:text-gray-100 focus:border-gray-300 dark:focus:border-gray-700 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
