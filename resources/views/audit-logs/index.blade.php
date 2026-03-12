<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Audit Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('audit-logs.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Event Filter -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">{{ __('Event') }}</label>
                                <select name="event" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                    <option value="">All Events</option>
                                    <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>Created</option>
                                    <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>Updated</option>
                                    <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                                </select>
                            </div>

                            <!-- User Filter -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">{{ __('User') }}</label>
                                <select name="user_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Model Filter -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">{{ __('Model') }}</label>
                                <select name="model" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                    <option value="">All Models</option>
                                    <option value="SeniorCitizen" {{ request('model') === 'SeniorCitizen' ? 'selected' : '' }}>Senior Citizen</option>
                                    <option value="User" {{ request('model') === 'User' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>

                            <!-- IP Address Search -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">{{ __('IP Address') }}</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search IP..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm" />
                            </div>

                            <!-- Filter Button -->
                            <div class="flex items-end">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-blue-700">
                                    {{ __('Filter') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Audit Logs Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-900 dark:text-gray-100">
                            <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">{{ __('Date & Time') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('User') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Event') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Model') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Record ID') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('IP Address') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Details') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditLogs as $log)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                        <td class="px-6 py-4">
                                            @if($log->user)
                                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded">
                                                    {{ $log->user->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-500">{{ __('System') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded font-semibold
                                                @if($log->event === 'created') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                                @elseif($log->event === 'updated') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                                                @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                                @endif">
                                                {{ ucfirst($log->event) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ class_basename($log->auditable_type) }}</td>
                                        <td class="px-6 py-4 font-mono text-gray-600 dark:text-gray-400">#{{ $log->auditable_id }}</td>
                                        <td class="px-6 py-4 font-mono text-sm">{{ $log->ip_address }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            @if($log->event === 'updated' && $log->old_values && $log->new_values)
                                                @php
                                                    $changed = array_keys(array_diff_assoc($log->new_values, $log->old_values));
                                                @endphp
                                                {{ implode(', ', $changed) ?: __('(no changed fields)') }}
                                            @elseif($log->event === 'created')
                                                {{ __('Created record') }}
                                            @elseif($log->event === 'deleted')
                                                {{ __('Deleted record') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('audit-logs.show', $log) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('No audit logs found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $auditLogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
