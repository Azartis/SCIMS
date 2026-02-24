<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Audit Log Details') }}
            </h2>
            <a href="{{ route('audit-logs.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                {{ __('Back to Logs') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Date & Time') }}</p>
                            <p class="text-lg font-semibold">{{ $auditLog->created_at->format('F d, Y H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('User') }}</p>
                            @if($auditLog->user)
                                <p class="text-lg font-semibold">{{ $auditLog->user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $auditLog->user->email }}</p>
                            @else
                                <p class="text-lg font-semibold">{{ __('System') }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Event') }}</p>
                            <span class="inline-block px-3 py-1 rounded font-semibold
                                @if($auditLog->event === 'created') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                @elseif($auditLog->event === 'updated') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                                @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                @endif">
                                {{ ucfirst($auditLog->event) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Model') }}</p>
                            <p class="text-lg font-semibold">{{ class_basename($auditLog->auditable_type) }} #{{ $auditLog->auditable_id }}</p>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('IP Address') }}</p>
                            <p class="font-mono text-lg">{{ $auditLog->ip_address }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('URL') }}</p>
                            <p class="text-sm text-blue-600 dark:text-blue-400 break-all">{{ $auditLog->url ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changes Card -->
            @if($auditLog->event === 'updated')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-6">{{ __('Changes Made') }}</h3>

                        @if($auditLog->old_values && $auditLog->new_values)
                            <div class="space-y-4">
                                @foreach($auditLog->new_values as $field => $newValue)
                                    @php
                                        $oldValue = $auditLog->old_values[$field] ?? null;
                                    @endphp
                                    @if($oldValue !== $newValue)
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                                {{ str_replace('_', ' ', ucfirst($field)) }}
                                            </p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-xs text-red-600 dark:text-red-400 font-semibold mb-1">{{ __('Before') }}</p>
                                                    <p class="font-mono text-sm bg-red-50 dark:bg-red-900/20 p-3 rounded border border-red-200 dark:border-red-700/50 break-all">
                                                        {{ $oldValue ?? '(empty)' }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-green-600 dark:text-green-400 font-semibold mb-1">{{ __('After') }}</p>
                                                    <p class="font-mono text-sm bg-green-50 dark:bg-green-900/20 p-3 rounded border border-green-200 dark:border-green-700/50 break-all">
                                                        {{ $newValue ?? '(empty)' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">{{ __('No changes recorded.') }}</p>
                        @endif
                    </div>
                </div>
            @elseif($auditLog->event === 'created')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-6">{{ __('Record Created With') }}</h3>

                        @if($auditLog->new_values)
                            <div class="space-y-4">
                                @foreach($auditLog->new_values as $field => $value)
                                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700/50">
                                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                            {{ str_replace('_', ' ', ucfirst($field)) }}
                                        </p>
                                        <p class="font-mono text-sm break-all">{{ $value ?? '(empty)' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">{{ __('No data recorded.') }}</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-6">{{ __('Record Deleted') }}</h3>

                        @if($auditLog->old_values)
                            <div class="space-y-4">
                                @foreach($auditLog->old_values as $field => $value)
                                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700/50">
                                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                            {{ str_replace('_', ' ', ucfirst($field)) }}
                                        </p>
                                        <p class="font-mono text-sm break-all">{{ $value ?? '(empty)' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">{{ __('No data recorded.') }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
