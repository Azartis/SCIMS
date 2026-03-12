@props([
    'name' => '',
    'label' => '',
    'placeholder' => 'Search...',
    'icon' => '🔍',
    'value' => '',
])

<div>
    <label for="{{ $name }}" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
        {{ $label }}
    </label>
    <input 
        type="text"
        id="{{ $name }}"
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
    />
</div>
