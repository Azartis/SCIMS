<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Change History') }} - {{ $seniorCitizen->getFormattedDisplayName() }}
            </h2>
            <a href="{{ route('senior-citizens.show', $seniorCitizen) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-blue-700">
                {{ __('Back to Record') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if ($auditLogs->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 dark:text-gray-400">No changes recorded yet for this record.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($auditLogs as $log)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mr-2"
                                            @if($log->event === 'created')
                                                style="background-color: #10b981; color: white;"
                                            @elseif($log->event === 'updated')
                                                style="background-color: #3b82f6; color: white;"
                                            @else
                                                style="background-color: #ef4444; color: white;"
                                            @endif>
                                            {{ strtoupper($log->event) }}
                                        </span>
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                        by <strong>{{ $log->user->name ?? 'System' }}</strong> on {{ $log->created_at->format('M d, Y H:i:s') }}
                                    </p>
                                </div>
                            </div>

                            @if ($log->event === 'updated' && $log->old_values && $log->new_values)
                                <div class="mt-4 space-y-3">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Changes:') }}</p>
                                    @foreach ($log->new_values as $key => $newValue)
                                        @if (isset($log->old_values[$key]) && $log->old_values[$key] !== $newValue)
                                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded border-l-4 border-blue-500">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ str_replace('_', ' ', ucfirst($key)) }}
                                                </p>
                                                <div class="mt-2 text-sm">
                                                    <p class="text-red-600 dark:text-red-400">
                                                        <strong>Before:</strong> 
                                                        @if (is_bool($log->old_values[$key]))
                                                            <span class="font-mono">{{ $log->old_values[$key] ? 'Yes' : 'No' }}</span>
                                                        @else
                                                            <span class="font-mono">{{ $log->old_values[$key] ?? 'null' }}</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-green-600 dark:text-green-400">
                                                        <strong>After:</strong> 
                                                        @if (is_bool($newValue))
                                                            <span class="font-mono">{{ $newValue ? 'Yes' : 'No' }}</span>
                                                        @else
                                                            <span class="font-mono">{{ $newValue ?? 'null' }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif ($log->event === 'created' && $log->new_values)
                                <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 rounded">
                                    <p class="text-sm text-green-700 dark:text-green-400">
                                        Record created with initial data
                                    </p>
                                </div>
                            @elseif ($log->event === 'deleted' && $log->old_values)
                                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded">
                                    <p class="text-sm text-red-700 dark:text-red-400">
                                        Record was archived/deleted
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>