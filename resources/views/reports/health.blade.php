<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Health Condition Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @foreach($conditions as $key => $label)
                    <a href="{{ route('reports.health', ['condition' => $key]) }}" class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:border-blue-500 border border-transparent">
                        <h3 class="text-lg font-semibold mb-2">{{ $label }}</h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $counts[$key] ?? 0 }}</p>
                    </a>
                @endforeach
            </div>

            @if(isset($condition) && $seniorCitizens)
                <!-- Modal for Health Condition Results -->
                <x-modal name="healthModal" :show="isset($condition)" focusable>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">{{ $conditions[$condition] ?? 'Results' }}</h3>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('reports.health') }}" class="px-3 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded">{{ __('Close') }}</a>
                            </div>
                        </div>

                        <!-- Filter Form -->
 <form method="GET" action="{{ route('reports.health') }}" 
      class="mb-4 flex flex-nowrap gap-2 items-center overflow-x-auto">

    <input type="hidden" name="condition" value="{{ $condition }}">

    <!-- Search -->
    <input type="text" 
        name="search" 
        placeholder="{{ __('Search name or OSCA ID') }}" 
        value="{{ request('search') }}" 
        class="w-[170px] px-2 py-1 rounded-md border-gray-300 
               dark:border-gray-600 dark:bg-gray-700 
               dark:text-gray-100 text-sm flex-shrink-0" />

    <!-- Barangay -->
    <select name="barangay" 
        class="w-[160px] px-2 py-1 rounded-md border-gray-300 
               dark:border-gray-600 dark:bg-gray-700 
               dark:text-gray-100 text-sm flex-shrink-0">
        <option value="">{{ __('All Barangays') }}</option>
        @foreach(\App\Constants\Barangay::list() as $barangay)
            <option value="{{ $barangay }}" {{ request('barangay') === $barangay ? 'selected' : '' }}>
                {{ $barangay }}
            </option>
        @endforeach
    </select>

    {{-- Condition Specific Filters --}}

    @if($condition === 'with_disability')
        <select name="type_of_disability" 
            class="w-[190px] px-2 py-1 rounded-md border-gray-300 
                   dark:border-gray-600 dark:bg-gray-700 
                   dark:text-gray-100 text-sm flex-shrink-0">
            <option value="">{{ __('Disability Type') }}</option>
            @foreach(['Deaf','Intellectual Disability','Learning Disability','Mental Disability','Physical Disability (Orthopedic)','Psychosocial Disability','Speech and Language Impairment','Visual Disability','Cancer(RA11215)','Rare Disease(RA10747)'] as $type)
                <option value="{{ $type }}" {{ request('type_of_disability') === $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
    @endif

    @if($condition === 'with_assistive_device')
        <input type="text" 
            name="type_of_assistive_device" 
            placeholder="{{ __('Device type') }}" 
            value="{{ request('type_of_assistive_device') }}" 
            class="w-[150px] px-2 py-1 rounded-md border-gray-300 
                   dark:border-gray-600 dark:bg-gray-700 
                   dark:text-gray-100 text-sm flex-shrink-0" />
    @endif

    @if($condition === 'with_critical_illness')
        <input type="text" 
            name="specify_illness" 
            placeholder="{{ __('Specify illness') }}" 
            value="{{ request('specify_illness') }}" 
            class="w-[160px] px-2 py-1 rounded-md border-gray-300 
                   dark:border-gray-600 dark:bg-gray-700 
                   dark:text-gray-100 text-sm flex-shrink-0" />
    @endif

    @if($condition === 'philhealth_member')
        <input type="text" 
            name="philhealth_id" 
            placeholder="{{ __('PhilHealth ID') }}" 
            value="{{ request('philhealth_id') }}" 
            class="w-[150px] px-2 py-1 rounded-md border-gray-300 
                   dark:border-gray-600 dark:bg-gray-700 
                   dark:text-gray-100 text-sm flex-shrink-0" />
    @endif

    <!-- Sex -->
    <select name="sex" 
        class="w-[100px] px-2 py-1 rounded-md border-gray-300 
               dark:border-gray-600 dark:bg-gray-700 
               dark:text-gray-100 text-sm flex-shrink-0">
        <option value="">{{ __('Sex') }}</option>
        <option value="Male" {{ request('sex') === 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
        <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
        <option value="Other" {{ request('sex') === 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
    </select>

    <!-- Age Range (Fixed Properly) -->
    <select name="age_range" 
        class="w-[110px] px-2 py-1 rounded-md border-gray-300 
               dark:border-gray-600 dark:bg-gray-700 
               dark:text-gray-100 text-sm flex-shrink-0">
        <option value="">{{ __('Age Range') }}</option>
        <option value="60-69" {{ request('age_range') === '60-69' ? 'selected' : '' }}>60-69</option>
        <option value="70-79" {{ request('age_range') === '70-79' ? 'selected' : '' }}>70-79</option>
        <option value="80+" {{ request('age_range') === '80+' ? 'selected' : '' }}>80+</option>
    </select>

    <!-- Sort -->
    <select name="sort" 
        class="w-[90px] px-2 py-1 rounded-md border-gray-300 
               dark:border-gray-600 dark:bg-gray-700 
               dark:text-gray-100 text-sm flex-shrink-0">
        <option value="asc" {{ request('sort') === 'asc' || !request('sort') ? 'selected' : '' }}>
            {{ __('A - Z') }}
        </option>
        <option value="desc" {{ request('sort') === 'desc' ? 'selected' : '' }}>
            {{ __('Z - A') }}
        </option>
    </select>

    <!-- Buttons -->
    <button type="submit" 
        class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs flex-shrink-0">
        {{ __('Filter') }}
    </button>

    <a href="{{ route('reports.health.export', request()->query()) }}" 
        class="px-3 py-1 bg-gray-800 text-white rounded-md text-xs flex-shrink-0">
        {{ __('Export CSV') }}
    </a>

    <a href="{{ route('reports.health', ['condition' => $condition]) }}" 
        class="px-3 py-1 bg-gray-500 text-white rounded-md text-xs flex-shrink-0">
        {{ __('Reset') }}
    </a>

</form>
                        <!-- Results Table -->
                        @if($seniorCitizens->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400 py-6">{{ __('No records found for this condition.') }}</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-900 dark:text-gray-100">
                                    <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <tr>
                                            <th class="px-4 py-3 font-semibold">{{ __('Full Name') }}</th>
                                            <th class="px-4 py-3 font-semibold">{{ __('Age') }}</th>
                                            <th class="px-4 py-3 font-semibold">{{ __('Sex') }}</th>
                                            <th class="px-4 py-3 font-semibold">{{ __('Barangay') }}</th>
                                            <th class="px-4 py-3 font-semibold">{{ __('OSCA ID') }}</th>
                                            @if($condition === 'with_disability')
                                                <th class="px-4 py-3 font-semibold">{{ __('Disability Type') }}</th>
                                            @elseif($condition === 'with_assistive_device')
                                                <th class="px-4 py-3 font-semibold">{{ __('Device') }}</th>
                                            @elseif($condition === 'with_critical_illness')
                                                <th class="px-4 py-3 font-semibold">{{ __('Illness') }}</th>
                                            @elseif($condition === 'philhealth_member')
                                                <th class="px-4 py-3 font-semibold">{{ __('PHIC ID') }}</th>
                                            @endif
                                            <th class="px-4 py-3 font-semibold">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($seniorCitizens as $citizen)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-4 py-3 font-medium">{{ $citizen->getFormattedDisplayName() }}</td>
                                                <td class="px-4 py-3">{{ $citizen->exact_age }}</td>
                                                <td class="px-4 py-3">{{ $citizen->sex }}</td>
                                                <td class="px-4 py-3">{{ $citizen->barangay ?? 'N/A' }}</td>
                                                <td class="px-4 py-3">{{ $citizen->osca_id }}</td>
                                                @if($condition === 'with_disability')
                                                    <td class="px-4 py-3">{{ $citizen->type_of_disability ?? '-' }}</td>
                                                @elseif($condition === 'with_assistive_device')
                                                    <td class="px-4 py-3">{{ $citizen->type_of_assistive_device ?? '-' }}</td>
                                                @elseif($condition === 'with_critical_illness')
                                                    <td class="px-4 py-3">{{ $citizen->specify_illness ?? '-' }}</td>
                                                @elseif($condition === 'philhealth_member')
                                                    <td class="px-4 py-3">{{ $citizen->philhealth_id ?? '-' }}</td>
                                                @endif
                                                <td class="px-4 py-3 flex gap-2">
                                                    <a href="{{ route('senior-citizens.show', $citizen) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">{{ __('View') }}</a>
                                                    <a href="{{ route('senior-citizens.edit', $citizen) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm">{{ __('Edit') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-600">
                                {{ $seniorCitizens->links() }}
                            </div>
                        @endif
                    </div>

                    <x-slot name="footer">
                        <a href="{{ route('reports.health') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400">
                            {{ __('Back to Categories') }}
                        </a>
                    </x-slot>
                </x-modal>
            @endif
        </div>
    </div>
</x-app-layout>