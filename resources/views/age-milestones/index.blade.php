<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Octogenarian Assistance') }}
                @if($selectedAge)
                    <span class="text-sm font-normal text-gray-600 dark:text-gray-400"> - Age {{ $selectedAge }}</span>
                @endif
            </h2>
            @if($selectedAge)
                <a href="{{ route('age-milestones.index') }}" class="text-sm px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                    ← View All Ages
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            


            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-800 dark:text-red-300 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($selectedAge)
                {{-- Single Age Group View --}}
                @php
                    $group = $ageGroups[$selectedAge] ?? null;
                    $colors = [
                        80 => ['bg' => 'bg-slate-50 dark:bg-slate-900/20', 'border' => 'border-slate-300 dark:border-slate-700', 'text' => 'text-slate-700 dark:text-slate-300', 'heading' => 'text-slate-900 dark:text-slate-100', 'button' => 'bg-slate-600 hover:bg-slate-700'],
                        85 => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'border' => 'border-blue-300 dark:border-blue-700', 'text' => 'text-blue-700 dark:text-blue-300', 'heading' => 'text-blue-900 dark:text-blue-100', 'button' => 'bg-blue-600 hover:bg-blue-700'],
                        90 => ['bg' => 'bg-purple-50 dark:bg-purple-900/20', 'border' => 'border-purple-300 dark:border-purple-700', 'text' => 'text-purple-700 dark:text-purple-300', 'heading' => 'text-purple-900 dark:text-purple-100', 'button' => 'bg-purple-600 hover:bg-purple-700'],
                        95 => ['bg' => 'bg-pink-50 dark:bg-pink-900/20', 'border' => 'border-pink-300 dark:border-pink-700', 'text' => 'text-pink-700 dark:text-pink-300', 'heading' => 'text-pink-900 dark:text-pink-100', 'button' => 'bg-pink-600 hover:bg-pink-700'],
                        100 => ['bg' => 'bg-yellow-50 dark:bg-yellow-900/20', 'border' => 'border-yellow-300 dark:border-yellow-700', 'text' => 'text-yellow-700 dark:text-yellow-300', 'heading' => 'text-yellow-900 dark:text-yellow-100', 'button' => 'bg-yellow-600 hover:bg-yellow-700'],
                    ];
                    $color = $colors[$selectedAge] ?? $colors[80];
                @endphp

                @if($group && $group['count'] > 0)
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-2xl font-bold {{ $color['heading'] }}">{{ $group['count'] }} {{ $group['count'] === 1 ? 'Senior' : 'Seniors' }} Age {{ $selectedAge }}</h3>
                            </div>
                            <span class="text-4xl">{{ $group['icon'] }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            @foreach($group['seniors'] as $senior)
                                <div class="{{ $color['bg'] }} {{ $color['border'] }} border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold {{ $color['heading'] }}">
                                                {{ $senior->firstname }} {{ $senior->lastname }}
                                                @if($senior->middlename)
                                                    <span class="text-sm">{{ substr($senior->middlename, 0, 1) }}.</span>
                                                @endif
                                                @if($senior->extension_name)
                                                    <span class="text-sm">{{ $senior->extension_name }}</span>
                                                @endif
                                            </h4>
                                            <p class="text-sm {{ $color['text'] }}">{{ $senior->barangay ?? 'No Barangay' }}</p>
                                        </div>
                                        <a href="{{ route('senior-citizens.show', $senior->id) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-semibold">View</a>
                                    </div>

                                    <div class="space-y-1 text-xs {{ $color['text'] }} mb-3">
                                        <p><strong>Contact:</strong> {{ $senior->contact_number ?? 'N/A' }}</p>
                                        <p><strong>Civil Status:</strong> {{ $senior->civil_status ?? 'N/A' }}</p>
                                        <p><strong>Sex:</strong> {{ $senior->sex === 'M' ? 'Male' : ($senior->sex === 'F' ? 'Female' : 'N/A') }}</p>
                                    </div>

                                    <div class="border-t {{ $color['border'] }} pt-2">
                                        @php
                                            $pendingCount = $senior->pensionDistributions()
                                                ->where('status', 'unclaimed')
                                                ->count();
                                            $claimedCount = $senior->pensionDistributions()
                                                ->where('status', 'claimed')
                                                ->count();
                                        @endphp
                                        <div class="flex gap-1 flex-wrap">
                                            @if($pendingCount > 0)
                                                <span class="text-[9px] bg-amber-200 dark:bg-amber-800 text-amber-900 dark:text-amber-100 px-2 py-1 rounded">{{ $pendingCount }} Pending</span>
                                            @endif
                                            @if($claimedCount > 0)
                                                <span class="text-[9px] bg-green-200 dark:bg-green-800 text-green-900 dark:text-green-100 px-2 py-1 rounded">{{ $claimedCount }} Received</span>
                                            @endif
                                            @if($pendingCount === 0 && $claimedCount === 0)
                                                <span class="text-[9px] text-gray-500 dark:text-gray-400">No distributions</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($group['count'] > 0)
                            <div class="flex justify-center">
                                <button type="button" 
                                        onclick="openDistributionModal({{ $selectedAge }}, {{ $group['count'] }})"
                                        class="{{ $color['button'] }} text-white px-8 py-3 rounded-lg text-lg font-semibold transition">
                                    + Distribute Assistance to Age {{ $selectedAge }}
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400 text-lg">No seniors found at age {{ $selectedAge }}</p>
                        <a href="{{ route('age-milestones.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline mt-4 inline-block">← Back to all ages</a>
                    </div>
                @endif

            @else
                {{-- All Ages View --}}
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
                    @php
                        $colors = [
                            80 => ['bg' => 'bg-slate-50 dark:bg-slate-900/20', 'border' => 'border-slate-300 dark:border-slate-700', 'text' => 'text-slate-700 dark:text-slate-300', 'heading' => 'text-slate-900 dark:text-slate-100', 'button' => 'bg-slate-600 hover:bg-slate-700'],
                            85 => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'border' => 'border-blue-300 dark:border-blue-700', 'text' => 'text-blue-700 dark:text-blue-300', 'heading' => 'text-blue-900 dark:text-blue-100', 'button' => 'bg-blue-600 hover:bg-blue-700'],
                            90 => ['bg' => 'bg-purple-50 dark:bg-purple-900/20', 'border' => 'border-purple-300 dark:border-purple-700', 'text' => 'text-purple-700 dark:text-purple-300', 'heading' => 'text-purple-900 dark:text-purple-100', 'button' => 'bg-purple-600 hover:bg-purple-700'],
                            95 => ['bg' => 'bg-pink-50 dark:bg-pink-900/20', 'border' => 'border-pink-300 dark:border-pink-700', 'text' => 'text-pink-700 dark:text-pink-300', 'heading' => 'text-pink-900 dark:text-pink-100', 'button' => 'bg-pink-600 hover:bg-pink-700'],
                            100 => ['bg' => 'bg-yellow-50 dark:bg-yellow-900/20', 'border' => 'border-yellow-300 dark:border-yellow-700', 'text' => 'text-yellow-700 dark:text-yellow-300', 'heading' => 'text-yellow-900 dark:text-yellow-100', 'button' => 'bg-yellow-600 hover:bg-yellow-700'],
                        ];
                    @endphp

                    @foreach($ageGroups as $age => $group)
                        <div class="{{ $colors[$age]['bg'] }} {{ $colors[$age]['border'] }} border rounded-lg shadow-md overflow-hidden flex flex-col">
                            <a href="{{ route('age-milestones.index', ['age' => $age]) }}" class="p-4 border-b {{ $colors[$age]['border'] }} hover:opacity-80 transition">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-bold {{ $colors[$age]['heading'] }}">Age {{ $age }}</h3>
                                    <span class="text-2xl">{{ $group['icon'] }}</span>
                                </div>
                                <p class="text-2xl font-bold {{ $colors[$age]['heading'] }}">{{ $group['count'] }}</p>
                                <p class="text-xs {{ $colors[$age]['text'] }}">{{ $group['count'] === 1 ? 'Senior' : 'Seniors' }}</p>
                            </a>

                            <div class="flex-1 overflow-y-auto p-4">
                                @if($group['seniors']->count() > 0)
                                    <div class="space-y-2 max-h-64">
                                        @foreach($group['seniors']->take(5) as $senior)
                                            <div class="p-2 bg-white dark:bg-gray-800 rounded border {{ $colors[$age]['border'] }} text-xs">
                                                <p class="font-semibold {{ $colors[$age]['heading'] }}">{{ $senior->firstname }} {{ $senior->lastname }}</p>
                                                <p class="text-[10px] {{ $colors[$age]['text'] }}">{{ $senior->barangay ?? 'N/A' }}</p>
                                            </div>
                                        @endforeach
                                        @if($group['seniors']->count() > 5)
                                            <p class="text-[10px] text-center {{ $colors[$age]['text'] }} py-2">+{{ $group['seniors']->count() - 5 }} more</p>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-center {{ $colors[$age]['text'] }} text-sm py-8">No seniors at this age</p>
                                @endif
                            </div>

                            @if($group['seniors']->count() > 0)
                                <div class="p-4 border-t {{ $colors[$age]['border'] }}">
                                    <a href="{{ route('age-milestones.index', ['age' => $age]) }}" class="{{ $colors[$age]['button'] }} text-white px-4 py-2 rounded text-sm font-semibold w-full transition inline-block text-center">
                                        View & Assist
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Distribution History -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Assistance Records</h3>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Age</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Senior Citizen</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Assistance Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $filterAge = $selectedAge ? [$selectedAge] : [80, 85, 90, 95, 100];
                                $distributions = \App\Models\PensionDistribution::whereIn('senior_citizen_id', 
                                    \App\Models\SeniorCitizen::whereIn('age', $filterAge)
                                        ->pluck('id')
                                )->orderBy('disbursement_date', 'desc')->with('seniorCitizen')->limit(50)->get();
                            @endphp
                            @forelse($distributions as $dist)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3 text-xs">{{ $dist->seniorCitizen->age }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        <a href="{{ route('senior-citizens.show', $dist->seniorCitizen->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $dist->seniorCitizen->firstname }} {{ $dist->seniorCitizen->lastname }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold">₱{{ number_format($dist->amount, 2) }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $dist->disbursement_date?->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            @if($dist->status === 'claimed')
                                                bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                            @else
                                                bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300
                                            @endif">
                                            @if($dist->status === 'claimed')
                                                Received
                                            @else
                                                Pending
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        @if($dist->status === 'unclaimed')
                                            <button type="button" 
                                                    onclick="openClaimModal({{ $dist->id }})"
                                                    class="text-green-600 dark:text-green-400 hover:underline font-semibold">
                                                Mark Received
                                            </button>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">{{ $dist->claimed_at?->format('M d, Y') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No distributions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Distribution Modal -->
    <div id="distributionModal" class="hidden fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between sticky top-0 bg-white dark:bg-gray-800">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white" id="modalTitle">Distribute Assistance</h3>
                <button type="button" onclick="closeDistributionModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>

            <form id="distributionForm" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        <input type="hidden" id="ageInput" name="age">
                        <span id="seniorCount"></span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assistance Date *</label>
                        <input type="date" name="disbursement_date" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assistance Amount *</label>
                        <input type="number" name="amount" step="0.01" min="0" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div id="seniorCheckboxes" class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg p-3 bg-gray-50 dark:bg-gray-700/50">
                    <!-- Checkboxes will be inserted here -->
                </div>

                <div class="flex gap-3 justify-end pt-4">
                    <button type="button" onclick="closeDistributionModal()" 
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Distribute Assistance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Claim Modal -->
    <div id="claimModal" class="hidden fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Mark Assistance as Received</h3>
                <button type="button" onclick="closeClaimModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>

            <form id="claimForm" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Authorized Representative Name</label>
                    <input type="text" name="authorized_rep_name" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Relationship to Senior</label>
                    <input type="text" name="authorized_rep_relationship" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Contact Number</label>
                    <input type="text" name="authorized_rep_contact" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex gap-3 justify-end pt-4">
                    <button type="button" onclick="closeClaimModal()" 
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Mark Received
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        @php
            $seniorsByAge = [];
            foreach($ageGroups as $age => $group) {
                $seniorsByAge[$age] = [];
                foreach($group['seniors'] as $senior) {
                    $seniorsByAge[$age][] = ['id' => $senior->id, 'name' => $senior->firstname . ' ' . $senior->lastname];
                }
            }
        @endphp
        const seniorsByAge = {!! json_encode($seniorsByAge) !!};

        function openDistributionModal(age, count) {
            const modal = document.getElementById('distributionModal');
            const form = document.getElementById('distributionForm');
            const ageInput = document.getElementById('ageInput');
            const seniorCount = document.getElementById('seniorCount');
            const checkboxesDiv = document.getElementById('seniorCheckboxes');

            ageInput.value = age;
            seniorCount.textContent = `Select seniors (Age ${age}) - ${count} available`;
            
            form.action = `/age-milestones/${age}/distribute`;
            
            // Get seniors for this age
            const seniors = seniorsByAge[age] || [];
            let html = '';
            seniors.forEach(senior => {
                html += `
                    <label class="flex items-center gap-2 p-2 hover:bg-gray-100 dark:hover:bg-gray-600 rounded cursor-pointer">
                        <input type="checkbox" name="senior_citizen_ids" value="${senior.id}" class="w-4 h-4">
                        <span class="text-sm text-gray-700 dark:text-gray-300">${senior.name}</span>
                    </label>
                `;
            });
            checkboxesDiv.innerHTML = html || '<p class="text-center text-gray-500">No seniors to select</p>';

            modal.classList.remove('hidden');
        }

        function closeDistributionModal() {
            document.getElementById('distributionModal').classList.add('hidden');
        }

        function openClaimModal(distributionId) {
            const modal = document.getElementById('claimModal');
            const form = document.getElementById('claimForm');
            form.action = `/age-milestones/distribution/${distributionId}/claim`;
            modal.classList.remove('hidden');
        }

        function closeClaimModal() {
            document.getElementById('claimModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('distributionModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDistributionModal();
        });
        document.getElementById('claimModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeClaimModal();
        });
    </script>

</x-app-layout>
