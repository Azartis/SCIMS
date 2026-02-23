<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Senior Citizens') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('senior-citizens.archive') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-gray-700">
                    {{ __('View Archive') }}
                </a>
                <a href="{{ route('senior-citizens.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:bg-blue-700 dark:focus:bg-blue-600 active:bg-blue-900 dark:active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Add Senior Citizen') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search and Filter Bar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('senior-citizens.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search by name, OSCA ID, contact..." 
                                value="{{ request('search') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm"
                            />
                        </div>

                        <!-- Gender Filter -->
                        <div>
                            <select name="gender" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                <option value="">Gender</option>
                                <option value="Male" {{ request('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ request('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ request('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Barangay Filter -->
                        <div>
                            <select name="barangay" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                <option value="">All Barangays</option>
                                @foreach(\App\Constants\Barangay::list() as $barangay)
                                    <option value="{{ $barangay }}" {{ request('barangay') === $barangay ? 'selected' : '' }}>
                                        {{ $barangay }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pension Type Filter -->
                        <div>
                            <select name="pension_type" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                <option value="">Pension Type</option>
                                <option value="none">None</option>
                                <option value="sss" {{ request('pension_type') === 'sss' ? 'selected' : '' }}>SSS</option>
                                <option value="gsis" {{ request('pension_type') === 'gsis' ? 'selected' : '' }}>GSIS</option>
                                <option value="pvao" {{ request('pension_type') === 'pvao' ? 'selected' : '' }}>PVAO</option>
                                <option value="family_pension" {{ request('pension_type') === 'family_pension' ? 'selected' : '' }}>Family Pension</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                <option value="">Status</option>
                                <option value="waitlist" {{ request('status') === 'waitlist' ? 'selected' : '' }}>Waitlist</option>
                                <option value="social_pension" {{ request('status') === 'social_pension' ? 'selected' : '' }}>Social Pension</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-blue-700">
                            {{ __('Search') }}
                        </button>
                        <a href="{{ route('senior-citizens.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if ($seniorCitizens->isEmpty())
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        {{ __('No senior citizens found. ') }}
                        <a href="{{ route('senior-citizens.create') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            {{ __('Add one now') }}
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-900 dark:text-gray-100">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">{{ __('Full Name') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Age') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Gender') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('OSCA ID') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Contact') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach ($seniorCitizens as $citizen)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium">{{ $citizen->getFormattedDisplayName() }}</td>
                                        <td class="px-6 py-4">{{ $citizen->age }}</td>
                                        <td class="px-6 py-4">{{ $citizen->gender }}</td>
                                        <td class="px-6 py-4">{{ $citizen->osca_id }}</td>
                                        <td class="px-6 py-4">{{ $citizen->contact_number ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 flex gap-2">
                                            <a href="{{ route('senior-citizens.show', $citizen) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('View') }}</a>
                                            <a href="{{ route('senior-citizens.edit', $citizen) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline">{{ __('Edit') }}</a>
                                            <form action="{{ route('senior-citizens.destroy', $citizen) }}" method="POST" style="display: inline;" onsubmit="return confirm('Archive this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">{{ __('Archive') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $seniorCitizens->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
