<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('View Senior Citizen - OSCA Intake Form') }}
            </h2>
            <a href="{{ route('senior-citizens.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- HEADER SECTION -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b-2 border-blue-500">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        OFFICE OF THE SENIOR CITIZEN AFFAIRS (OSCA) INTAKE FORM
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        Senior Citizen: {{ $seniorCitizen->getFormattedDisplayName() }}
                    </p>
                </div>
            </div>

            <!-- SECTION 1: PERSONAL / BASIC INFORMATION -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                        1️⃣ PERSONAL / BASIC INFORMATION
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">OSCA ID Number</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $seniorCitizen->osca_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Name</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $seniorCitizen->lastname }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">First Name</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $seniorCitizen->firstname }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Middle Name</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $seniorCitizen->middlename ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Complete Address</p>
                        <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->address }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Barangay</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->barangay }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Contact Number</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->contact_number ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Date of Birth</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->date_of_birth ? $seniorCitizen->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Place of Birth</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->place_of_birth ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sex</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->sex }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Civil Status</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->civil_status ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Citizenship</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->citizenship ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Religion</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->religion ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Educational Attainment</p>
                        <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->educational_attainment ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: HEALTH CONDITION -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                        2️⃣ HEALTH CONDITION
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">With Disability</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->with_disability ? '✓ Yes' : '✗ No' }}</p>
                            @if($seniorCitizen->with_disability)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Type: {{ $seniorCitizen->type_of_disability ?? 'Not specified' }}</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bedridden</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->bedridden ? '✓ Yes' : '✗ No' }}</p>
                        </div>

                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">With Assistive Device</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->with_assistive_device ? '✓ Yes' : '✗ No' }}</p>
                            @if($seniorCitizen->with_assistive_device)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Type: {{ $seniorCitizen->type_of_assistive_device ?? 'Not specified' }}</p>
                            @endif
                        </div>

                        <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">With Critical Illness</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->with_critical_illness ? '✓ Yes' : '✗ No' }}</p>
                            @if($seniorCitizen->with_critical_illness)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $seniorCitizen->specify_illness ?? 'Not specified' }}</p>
                            @endif
                        </div>

                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">PhilHealth Member</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->philhealth_member ? '✓ Yes' : '✗ No' }}</p>
                            @if($seniorCitizen->philhealth_member)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">ID: {{ $seniorCitizen->philhealth_id ?? 'Not provided' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: SOURCE OF INCOME -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                        3️⃣ SOURCE OF INCOME
                    </h3>

                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg mb-6">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Is a Pensioner</p>
                        <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->is_pensioner ? '✓ Yes' : '✗ No' }}</p>
                        @if($seniorCitizen->is_pensioner)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Type: {{ $seniorCitizen->pension_type ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Amount: ₱{{ number_format($seniorCitizen->monthly_pension_amount ?? 0, 2) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Other Income Source</p>
                        <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->other_income_source ?? 'None' }}</p>
                    </div>

                    <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Monthly Income</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">₱{{ number_format($seniorCitizen->total_monthly_income ?? 0, 2) }}</p>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Indigent / Low Income</p>
                        <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->is_indigent ? '✓ Yes' : '✗ No' }}</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: FAMILY COMPOSITION -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                        4️⃣ FAMILY COMPOSITION
                    </h3>

                    @forelse($seniorCitizen->familyMembers as $member)
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-l-4 border-blue-500">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Name</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $member->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Relationship</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $member->relationship }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Age</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $member->age }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Civil Status</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $member->civil_status }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Occupation</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $member->occupation }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Income</p>
                                    <p class="text-gray-900 dark:text-gray-100">₱{{ number_format($member->monthly_income ?? 0, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Address</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $member->address }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-400">No family members recorded</p>
                    @endforelse
                </div>
            </div>

            <!-- SECTION 5: ADDITIONAL INFORMATION & REMARKS -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-blue-400">
                        5️⃣ ADDITIONAL INFORMATION & REMARKS
                    </h3>

                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Remarks</p>
                        <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->remarks ?? 'None' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">On Waitlist</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->waitlist ? '✓ Yes' : '✗ No' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Social Pension Recipient</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $seniorCitizen->social_pension ? '✓ Yes' : '✗ No' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 6: CHANGE HISTORY -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 pb-3 border-b-2 border-purple-400">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            📋 CHANGE HISTORY
                        </h3>
                        <a href="{{ route('senior-citizens.audit-history', $seniorCitizen) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-semibold">
                            View Full History →
                        </a>
                    </div>

                    @php
                        $recentChanges = $seniorCitizen->recentAuditLogs(5);
                    @endphp

                    @if ($recentChanges->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">No changes recorded yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentChanges as $log)
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded border-l-4"
                                    @if($log->event === 'created')
                                        style="border-left-color: #10b981;"
                                    @elseif($log->event === 'updated')
                                        style="border-left-color: #3b82f6;"
                                    @else
                                        style="border-left-color: #ef4444;"
                                    @endif>
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                <span class="inline-block px-2 py-1 rounded text-xs font-bold mr-2"
                                                    @if($log->event === 'created')
                                                        style="background-color: #10b981; color: white;"
                                                    @elseif($log->event === 'updated')
                                                        style="background-color: #3b82f6; color: white;"
                                                    @else
                                                        style="background-color: #ef4444; color: white;"
                                                    @endif>
                                                    {{ strtoupper($log->event) }}
                                                </span>
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                by <strong>{{ $log->user->name ?? 'System' }}</strong> • {{ $log->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($log->event === 'updated' && $log->old_values && $log->new_values)
                                        <div class="mt-2 text-xs">
                                            @php
                                                $changedFields = array_diff_assoc($log->new_values, $log->old_values);
                                            @endphp
                                            @if (count($changedFields) > 0)
                                                <p class="text-gray-700 dark:text-gray-300 font-semibold">Fields changed:</p>
                                                <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 ml-1">
                                                    @foreach ($changedFields as $key => $value)
                                                        <li>{{ str_replace('_', ' ', ucfirst($key)) }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="flex gap-2 justify-end mb-6">
                <a href="{{ route('senior-citizens.edit', $seniorCitizen) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 dark:bg-yellow-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-yellow-700 transition">
                    {{ __('Edit') }}
                </a>
                <form action="{{ route('senior-citizens.destroy', $seniorCitizen) }}" method="POST" style="display: inline;" onsubmit="return confirm('Archive this senior citizen? You can restore it later from the Archive section.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-red-700 transition">
                        {{ __('Archive') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
