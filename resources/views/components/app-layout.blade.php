@props(['header'])

<x-layouts.app :header="$header ?? null">
    {{ $slot }}
</x-layouts.app>
