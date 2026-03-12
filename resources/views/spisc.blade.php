<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">SPISC</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Social Pension Recipients - Claim Status Tracking</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- Important Notice: Deceased Quarterly Restriction -->
            <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/50 rounded-lg flex gap-4">
                <div class="flex-shrink-0 text-2xl">ℹ️</div>
                <div>
                    <h3 class="font-semibold text-blue-900 dark:text-blue-100">Quarterly Pension Restriction for Deceased Recipients</h3>
                    <p class="text-sm text-blue-800 dark:text-blue-200 mt-1">
                        <strong>Important Rule:</strong> Deceased social pension recipients can 
                        <strong>ONLY receive pension distributions for the quarter in which they died</strong>. 
                        The system will automatically reject distributions for other quarters.
                    </p>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-2">
                        📌 Check the "Vital Status" column to identify deceased recipients (marked with ☠️) and their death dates. 
                        See the <a href="{{ asset('DECEASED_MANAGEMENT_GUIDE.md') }}" class="underline font-semibold hover:no-underline">Deceased Management Guide</a> for detailed information.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <button id="open-add-distribution" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 dark:bg-green-700 text-white rounded-lg font-semibold hover:bg-green-700 dark:hover:bg-green-600 transition shadow-sm">
                    ➕ Add Distribution
                </button>
            </div>

            <x-filter-bar
                :action="route('spisc.index')"
                :resetUrl="route('spisc.index')"
                :hasActiveFilters="request()->filled('search') || request()->filled('barangay') || request()->filled('status') || request()->filled('deceased')"
                :activeCount="optional($filterService)->getActiveFilterCount() ?? 0"
            >
                <div class="sm:col-span-2 md:col-span-2">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Last or first name"
                        class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Barangay</label>
                    <select name="barangay" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">All Barangays</option>
                        @foreach($barangays as $brgy)
                            <option value="{{ $brgy }}" @selected(request('barangay') === $brgy)>{{ $brgy }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                    <select name="status" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="claimed_personal" @selected(request('status') === 'claimed_personal')>✓ Personal</option>
                        <option value="claimed_representative" @selected(request('status') === 'claimed_representative')>👤 Rep</option>
                        <option value="unclaimed" @selected(request('status') === 'unclaimed')>⏳ Unclaimed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Vital Status</label>
                    <select name="deceased" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="alive" @selected(request('deceased') === 'alive')>Alive</option>
                        <option value="only" @selected(request('deceased') === 'only')>Deceased</option>
                    </select>
                </div>
                <div>
                    <x-sort-dropdown :options="['name_asc' => 'Name A → Z', 'name_desc' => 'Name Z → A']" />
                </div>
            </x-filter-bar>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Results Summary -->
            <div class="mb-6 flex items-center justify-between">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Showing <strong>{{ $seniors->count() }}</strong> of <strong>{{ $seniors->total() }}</strong> social pension recipients
                </p>
            </div>

            <!-- Records Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if($seniors->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-900 dark:text-gray-100">Name</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-900 dark:text-gray-100">Barangay</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-900 dark:text-gray-100">Address</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-900 dark:text-gray-100">Birthdate</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-gray-100">Vital Status</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-gray-100">Claim Status</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($seniors as $senior)
                                    @php
                                        $latestDistribution = $senior->pensiondistributions->first();
                                        $isClaimedByRep = $latestDistribution && $latestDistribution->status === 'claimed' && $latestDistribution->authorized_rep_name;
                                        $isClaimedPersonal = $latestDistribution && $latestDistribution->status === 'claimed' && !$latestDistribution->authorized_rep_name;
                                        $isUnclaimed = $latestDistribution && $latestDistribution->status === 'unclaimed';
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $senior->lastname }}, {{ $senior->firstname }}
                                                </p>
                                                @if($senior->middlename || $senior->extension_name)
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        {{ $senior->middlename }}{{ $senior->extension_name ? ' ' . $senior->extension_name : '' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $senior->barangay }}</td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $senior->address ?? '—' }}</td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                            {{ $senior->date_of_birth ? $senior->date_of_birth->format('M d, Y') : '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($senior->date_of_death || $senior->remarks === 'Deceased')
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                                    ☠️ Deceased
                                                </span>
                                                @if($senior->date_of_death)
                                                    <div class="text-xs text-red-600 dark:text-red-400 mt-1 font-medium">
                                                        {{ $senior->date_of_death->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-xs text-red-500 dark:text-red-400 mt-0.5">
                                                        ⚠️ Quarter restriction
                                                    </div>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                    ✓ Alive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($isClaimedPersonal)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                    ✓ Claimed (Personal)
                                                </span>
                                            @elseif($isClaimedByRep)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                                    👤 Claimed (Rep)
                                                </span>
                                            @elseif($isUnclaimed)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                                    ⏳ Unclaimed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400">
                                                    — No Record
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                                    @if($latestDistribution)
                                                <button type="button" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold text-sm open-status-modal" data-senior-id="{{ $senior->id }}" data-distribution-id="{{ $latestDistribution->id }}" data-status="{{ $latestDistribution->status }}" data-rep-name="{{ $latestDistribution->authorized_rep_name }}" data-rep-relationship="{{ $latestDistribution->authorized_rep_relationship }}" data-rep-contact="{{ $latestDistribution->authorized_rep_contact }}" data-senior-name="{{ $senior->lastname }}, {{ $senior->firstname }}">
                                                    📝 Update
                                                </button>
                                            @else
                                                <button type="button" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold text-sm open-add-distribution" data-senior-id="{{ $senior->id }}">
                                                    ➕ Add Distribution
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $seniors->links() }}
                    </div>
                @else
                    <div class="p-8 text-center text-gray-600 dark:text-gray-400">
                        <p class="text-lg font-semibold">No records found</p>
                        <p class="text-sm mt-2">Try adjusting your filters to find social pension recipients.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Distribution Modal (initially hidden) -->
    <x-modal name="distributionModal" :show="false" focusable maxWidth="md">
        <div class="p-6" id="distribution-modal-content">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">New Pension Distribution</h3>
            <form action="{{ route('pension-distributions.store') }}" method="post" class="space-y-4" id="distribution-form">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Disbursement Date</label>
                    <input type="date" name="disbursement_date" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount (per recipient)</label>
                    <input type="number" name="amount" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>

                <div>
                    <p class="font-medium text-gray-700">Select Recipients</p>
                    <p class="text-xs text-gray-500">Choose the social pension recipients who will receive this disbursement.</p>
                    <div class="mt-3 max-h-64 overflow-y-auto border rounded p-2 bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center mb-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="select-all-recipients" class="form-checkbox">
                                <span class="ml-2 text-sm font-medium">Select all</span>
                            </label>
                        </div>
                        @forelse($allSeniors as $senior)
                            <label class="flex items-center gap-3 p-2 hover:bg-white dark:hover:bg-gray-700 rounded">
                                <input type="checkbox" name="senior_citizen_ids[]" value="{{ $senior->id }}" class="form-checkbox recipient-checkbox" data-date-of-death="{{ $senior->date_of_death?->toDateString() }}">
                                <div>
                                    <div class="font-semibold">{{ $senior->lastname }}, {{ $senior->firstname }} @if($senior->middlename) {{ $senior->middlename }} @endif</div>
                                    <div class="text-xs text-gray-500">{{ $senior->barangay }} • {{ $senior->date_of_birth?->format('Y') ? $senior->age : '—' }} yrs</div>
                                </div>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500">No social pension recipients found.</p>
                        @endforelse
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'distributionModal' }));">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded">Confirm & Create</button>
                </div>
            </form>
        </div>
    </x-modal>


    <!-- Status Update Modal -->
    <div id="status-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-lg w-full mx-4 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Update Claim Status</h3>
            
            <form id="status-form" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="distribution_id" id="distribution-id">

                <!-- Senior Name Display -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-xs text-gray-600 dark:text-gray-400">Senior Citizen</p>
                    <p id="senior-name-display" class="text-lg font-semibold text-gray-900 dark:text-gray-100"></p>
                </div>

                <!-- Status Radio Options -->
                <div class="space-y-3">
                    <label class="flex items-center p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="radio" name="status" value="claimed_personal" class="w-4 h-4 text-green-600">
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">✓ Claimed by Senior (Personal)</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Senior citizen claimed the pension themselves</p>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="radio" name="status" value="claimed_representative" class="w-4 h-4 text-blue-600">
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">👤 Claimed by Representative</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Authorized representative claimed on behalf</p>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="radio" name="status" value="unclaimed" class="w-4 h-4 text-yellow-600">
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">⏳ Unclaimed</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Pension has not been claimed yet</p>
                        </div>
                    </label>
                </div>

                <!-- Representative Info Section (Hidden by default) -->
                <div id="rep-info-section" class="hidden p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg space-y-3">
                    <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Representative Information</p>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Representative Name</label>
                        <input type="text" name="authorized_rep_name" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 transition" placeholder="Enter representative name">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Relationship</label>
                        <input type="text" name="authorized_rep_relationship" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 transition" placeholder="e.g., Son, Daughter, Sibling">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Contact Number</label>
                        <input type="text" name="authorized_rep_contact" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 transition" placeholder="09XX-XXX-XXXX">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" class="flex-1 px-4 py-2.5 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-gray-100 rounded-lg font-semibold hover:bg-gray-400 transition" onclick="closeStatusModal()">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 dark:bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        ✓ Save Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal handling
        function openStatusModal(event) {
            const btn = event.target.closest('.open-status-modal');
            const seniorId = btn.dataset.seniorId;
            const distributionId = btn.dataset.distributionId;
            const currentStatus = btn.dataset.status;
            const repName = btn.dataset.repName;
            const repRelationship = btn.dataset.repRelationship;
            const repContact = btn.dataset.repContact;
            const seniorName = btn.dataset.seniorName;
            
            document.getElementById('distribution-id').value = distributionId;
            document.getElementById('senior-name-display').textContent = seniorName;
            document.getElementById('status-form').action = `/spisc/${seniorId}/update-status`;
            
            // Clear all inputs
            document.querySelector('input[name="authorized_rep_name"]').value = '';
            document.querySelector('input[name="authorized_rep_relationship"]').value = '';
            document.querySelector('input[name="authorized_rep_contact"]').value = '';
            
            // Set current status
            if (currentStatus === 'claimed' && repName) {
                document.querySelector('input[value="claimed_representative"]').checked = true;
                document.querySelector('input[name="authorized_rep_name"]').value = repName || '';
                document.querySelector('input[name="authorized_rep_relationship"]').value = repRelationship || '';
                document.querySelector('input[name="authorized_rep_contact"]').value = repContact || '';
            } else if (currentStatus === 'claimed') {
                document.querySelector('input[value="claimed_personal"]').checked = true;
            } else {
                document.querySelector('input[value="unclaimed"]').checked = true;
            }

            // Update rep section visibility
            updateRepSectionVisibility();

            document.getElementById('status-modal').classList.remove('hidden');
            document.getElementById('status-modal').classList.add('flex');
        }

        function closeStatusModal() {
            document.getElementById('status-modal').classList.remove('flex');
            document.getElementById('status-modal').classList.add('hidden');
        }

        function updateRepSectionVisibility() {
            const repSection = document.getElementById('rep-info-section');
            const selectedStatus = document.querySelector('input[name="status"]:checked')?.value;
            if (selectedStatus === 'claimed_representative') {
                repSection.classList.remove('hidden');
            } else {
                repSection.classList.add('hidden');
            }
        }

        // Toggle representative info section
        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', updateRepSectionVisibility);
        });

        // Attach click handlers
        document.querySelectorAll('.open-status-modal').forEach(btn => {
            btn.addEventListener('click', openStatusModal);
        });

        // Close modal on outside click
        document.getElementById('status-modal')?.addEventListener('click', e => {
            if (e.target.id === 'status-modal') closeStatusModal();
        });

        // Distribution modal handling
        function openDistributionModal(selectedId = null) {
            // reset form
            const form = document.getElementById('distribution-form');
            form.reset();
            // clear previous selections
            document.querySelectorAll('.recipient-checkbox').forEach(cb => {
                cb.checked = false;
                cb.disabled = false;
                cb.closest('label').classList.remove('opacity-50');
            });
            // if an id given, preselect it
            if (selectedId) {
                const cb = document.querySelector('.recipient-checkbox[value="' + selectedId + '"]');
                if (cb) {
                    cb.checked = true;
                    // optionally disable others?
                }
            }
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'distributionModal' }));
        }

        document.getElementById('open-add-distribution')?.addEventListener('click', () => openDistributionModal());
        document.querySelectorAll('.open-add-distribution').forEach(btn => {
            btn.addEventListener('click', e => {
                const id = e.currentTarget.dataset.seniorId;
                openDistributionModal(id);
            });
        });

        // Modal eligibility and select-all logic
        const selectAll = document.getElementById('select-all-recipients');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.recipient-checkbox').forEach(cb => cb.checked = this.checked && !cb.disabled);
            });
        }
        const disDateInput = document.querySelector('input[name="disbursement_date"]');
        function updateEligibility() {
            if (!disDateInput || !disDateInput.value) return;
            const d = new Date(disDateInput.value);
            const month = d.getMonth()+1;
            const q = Math.ceil(month/3);
            const quarterStartMonth = (q-1)*3 + 1;
            const quarterStart = new Date(d.getFullYear(), quarterStartMonth-1, 1);
            document.querySelectorAll('.recipient-checkbox').forEach(cb => {
                const death = cb.dataset.dateOfDeath;
                if (death) {
                    const dd = new Date(death);
                    if (dd < quarterStart) {
                        cb.disabled = true;
                        cb.checked = false;
                        cb.closest('label').classList.add('opacity-50');
                    } else {
                        cb.disabled = false;
                        cb.closest('label').classList.remove('opacity-50');
                    }
                } else {
                    cb.disabled = false;
                    cb.closest('label').classList.remove('opacity-50');
                }
            });
        }
        if (disDateInput) {
            disDateInput.addEventListener('change', updateEligibility);
            updateEligibility();
        }
    </script>
</x-app-layout>