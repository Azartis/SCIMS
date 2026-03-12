@props([
    'name' => 'age_range',
    'value' => null,
    'compact' => true,
])

@php
$ranges = ['60-69', '70-79', '80+'];
$current = old($name, $value);
$exactValue = old('age_exact', request('age_exact'));
$exactValue = $exactValue && is_numeric($exactValue) ? $exactValue : '';
@endphp

<div x-data="{ }">
    <label for="{{ $name }}" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Age</label>

    <div class="grid grid-cols-2 gap-1.5">
        <div>
            <select
                name="{{ $name }}"
                id="{{ $name }}"
                x-ref="range"
                @change="if ($event.target.value) { $refs.exact.value = ''; }"
                class="w-full px-2 py-1.5 text-xs rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                title="Age range"
            >
                <option value="">Range</option>
                @foreach($ranges as $r)
                    <option value="{{ $r }}" {{ $current === $r ? 'selected' : '' }}>{{ $r }} yrs</option>
                @endforeach
            </select>
        </div>

        <div>
            <input
                type="number"
                name="age_exact"
                min="60"
                max="120"
                placeholder="Age"
                x-ref="exact"
                @input="if ($event.target.value) { $refs.range.value = ''; }"
                value="{{ $exactValue }}"
                class="w-full px-2 py-1.5 text-xs rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                title="Exact age"
            />
        </div>
    </div>
</div>