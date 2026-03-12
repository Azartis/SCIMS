@props([
    'name' => '',
    'label' => '',
    'icon' => '📋',
    'value' => '',
    'options' => [],
    'placeholder' => 'Select...',
    'multiple' => false,
])

<div>
    <label for="{{ $name }}" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
        {{ $label }}
    </label>
    <select 
        id="{{ $name }}"
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        {{ $multiple ? 'multiple' : '' }}
        class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ (is_array($value) ? in_array($optionValue, $value) : $value === $optionValue) ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
