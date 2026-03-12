<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $seniorCitizen->getFormattedDisplayName() }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">OSCA ID: <span class="font-semibold">{{ $seniorCitizen->osca_id }}</span></p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('senior-citizens.edit', $seniorCitizen) }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 dark:bg-yellow-700 text-white rounded-lg font-semibold hover:bg-yellow-700 transition">
                    ✏️ Edit
                </a>
                <a href="{{ route('senior-citizens.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg font-semibold hover:bg-gray-300 transition">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Tabs Navigation -->
        <div x-data="{ activeTab: 'basic' }" class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">


            <!-- HEADER SECTION -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap border-b border-gray-200 dark:border-gray-700">
                    <button @click="activeTab = 'basic'" :class="{ 'border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20': activeTab === 'basic' }" class="px-6 py-4 font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition">
                        📋 Basic Info
                    </button>
                    <button @click="activeTab = 'health'" :class="{ 'border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20': activeTab === 'health' }" class="px-6 py-4 font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition">
                        🏥 Health
                    </button>
                    <button @click="activeTab = 'income'" :class="{ 'border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20': activeTab === 'income' }" class="px-6 py-4 font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition">
                        💰 Income
                    </button>
                    <button @click="activeTab = 'family'" :class="{ 'border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20': activeTab === 'family' }" class="px-6 py-4 font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition">
                        👨‍👩‍👧 Family
                    </button>
                    <button @click="activeTab = 'history'" :class="{ 'border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20': activeTab === 'history' }" class="px-6 py-4 font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition">
                        📜 History
                    </button>
                </div>

                <!-- Basic Info Tab -->
                <div x-show="activeTab === 'basic'" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">First Name</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $seniorCitizen->firstname }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Last Name</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $seniorCitizen->lastname }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Middle Name</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $seniorCitizen->middlename ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">Age</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $seniorCitizen->age }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Personal Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Date of Birth</p>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->date_of_birth ? $seniorCitizen->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Sex</p>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->sex }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Civil Status</p>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->civil_status ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Religion</p>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->religion ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Address & Contact</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Barangay</p>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->barangay }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Complete Address</p>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->address }}</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Contact Number</p>
                                    <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->contact_number ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                                    <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->email ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Health Tab -->
                <div x-show="activeTab === 'health'" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-6 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">With Disability</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $seniorCitizen->with_disability ? '✓ Yes' : '✗ No' }}</p>
                            @if($seniorCitizen->with_disability)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Type: {{ $seniorCitizen->type_of_disability ?? 'Not specified' }}</p>
                            @endif
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-6 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Bedridden</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ $seniorCitizen->bedridden ? '✓ Yes' : '✗ No' }}</p>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-6 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">PhilHealth Member</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $seniorCitizen->philhealth_member ? '✓ Yes' : '✗ No' }}</p>
                            @if($seniorCitizen->philhealth_member)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">ID: {{ $seniorCitizen->philhealth_id ?? 'Not provided' }}</p>
                            @endif
                        </div>

                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-6 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Critical Illness</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $seniorCitizen->with_critical_illness ? '✓ Yes' : '✗ No' }}</p>
                            @if($seniorCitizen->with_critical_illness)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $seniorCitizen->specify_illness ?? 'Not specified' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Assistive Devices</h4>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">With Assistive Device: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $seniorCitizen->with_assistive_device ? 'Yes' : 'No' }}</span></p>
                        @if($seniorCitizen->with_assistive_device)
                            <p class="text-gray-600 dark:text-gray-400">Type: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $seniorCitizen->type_of_assistive_device ?? 'Not specified' }}</span></p>
                        @endif
                    </div>
                </div>

                <!-- Income Tab -->
                <div x-show="activeTab === 'income'" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 p-6 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pensioner</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ $seniorCitizen->is_pensioner ? '✓ Yes' : '✗ No' }}</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-6 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Social Pension</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $seniorCitizen->social_pension ? '✓ Yes' : '✗ No' }}</p>
                        </div>
                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 p-6 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Indigent/Low Income</p>
                            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-2">{{ $seniorCitizen->is_indigent ? '✓ Yes' : '✗ No' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pension Type</p>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->pension_type ? ucfirst(str_replace('_', ' ', $seniorCitizen->pension_type)) : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Monthly Pension Amount</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">₱{{ number_format($seniorCitizen->monthly_pension_amount ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Other Income Source</p>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $seniorCitizen->other_income_source ?? 'None' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Monthly Income</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($seniorCitizen->total_monthly_income ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Family Tab -->
                <div x-show="activeTab === 'family'" class="p-8 space-y-4">
                    @forelse($seniorCitizen->familyMembers as $member)
                        <div class="bg-gray-50 dark:bg-gray-700 border-l-4 border-blue-500 p-6 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Name</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $member->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Relationship</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $member->relationship }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Age</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $member->age }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Occupation</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $member->occupation ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Monthly Income</p>
                                    <p class="text-gray-900 dark:text-gray-100">₱{{ number_format($member->monthly_income ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 dark:bg-gray-700 p-8 rounded-lg text-center">
                            <p class="text-gray-600 dark:text-gray-400">No family members recorded</p>
                        </div>
                    @endforelse
                </div>

                <!-- History Tab -->
                <div x-show="activeTab === 'history'" class="p-8 space-y-4">
                    @php
                        $recentChanges = $seniorCitizen->recentAuditLogs(10);
                    @endphp

                    @if ($recentChanges->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400 text-center py-8">No changes recorded yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentChanges as $log)
                                <div class="border-l-4 p-4 rounded" style="border-left-color: {{ $log->event === 'created' ? '#10b981' : ($log->event === 'updated' ? '#3b82f6' : '#ef4444') }}; background-color: {{ $log->event === 'created' ? '#f0fdf4' : ($log->event === 'updated' ? '#f0f9ff' : '#fef2f2') }};">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-block px-3 py-1 rounded text-xs font-bold text-white" style="background-color: {{ $log->event === 'created' ? '#10b981' : ($log->event === 'updated' ? '#3b82f6' : '#ef4444') }};">
                                                {{ strtoupper($log->event) }}
                                            </span>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $log->user->name ?? 'System' }}</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($log->event === 'updated' && $log->old_values && $log->new_values)
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            @php
                                                $changedFields = array_diff_assoc($log->new_values, $log->old_values);
                                            @endphp
                                            @if (count($changedFields) > 0)
                                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Fields changed:</p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($changedFields as $key => $value)
                                                        <x-badge color="blue" variant="outline">{{ str_replace('_', ' ', ucfirst($key)) }}</x-badge>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-6">
                            <a href="{{ route('senior-citizens.audit-history', $seniorCitizen) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                                View Full History →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-between items-center p-6 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex gap-3">
                <a href="{{ route('senior-citizens.edit', $seniorCitizen) }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 dark:bg-yellow-700 text-white rounded-lg font-semibold hover:bg-yellow-700 transition">
                    ✏️ Edit Record
                </a>
            </div>
            <a href="{{ route('senior-citizens.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                ← Back to Masterlist
            </a>
        </div>
    </div>
</x-app-layout>
