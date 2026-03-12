@props([
    'name' => 'sort',
    'options' => null,
])

@php
$defaultOptions = [
    'name_asc' => 'Name A → Z',
    'name_desc' => 'Name Z → A',
    'age_asc' => 'Age (Low → High)',
    'age_desc' => 'Age (High → Low)',
    'barangay_asc' => 'Barangay A → Z',
    'barangay_desc' => 'Barangay Z → A',
];
$options = $options ?? $defaultOptions;
$current = request($name, 'name_asc');
@endphp

<div>
    <label for="{{ $name }}" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Sort</label>
    <select name="{{ $name }}" id="{{ $name }}" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ $current === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>
