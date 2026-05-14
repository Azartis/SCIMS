<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Barangay Reports') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Click a barangay on the map to view senior citizen records') }}</p>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/osmtogeojson@3.0.0/osmtogeojson.js"></script>
    <style>.barangay-marker { background: transparent !important; border: none !important; }</style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-6">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('Map of Dulag, Leyte') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Click a barangay area to view senior citizens in that barangay') }}</p>
                </div>
                <div id="dulag-map" class="w-full min-h-[400px]" style="height: 520px; background: #e2e8f0;"></div>
                <div id="map-status" class="px-4 py-2 text-xs text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
                    {{ __('Loading Dulag boundary…') }}
                </div>
                <div class="p-3 border-t border-slate-200 dark:border-slate-700 flex flex-wrap gap-2">
                    @foreach($barangays as $b)
                        <a href="{{ route('reports.barangay', ['barangay' => $b]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-blue-100 dark:hover:bg-blue-900/40 hover:text-blue-700 dark:hover:text-blue-300 transition">
                            <span class="font-medium">{{ $b }}</span>
                            <span class="text-slate-500 dark:text-slate-400">({{ $counts[$b] ?? 0 }})</span>
                        </a>
                    @endforeach
                </div>
            </div>

            @push('scripts')
            <script>
            (function() {
                const counts = @json($counts ?? []);
                const baseUrl = @json(route('reports.barangay'));
                const statusEl = document.getElementById('map-status');
                const map = L.map('dulag-map', { zoomControl: true }).setView([10.9525, 125.0321], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

                // Load boundaries from our own backend (same-origin), cached server-side.
                const geoUrl = @json(route('geo.dulag-barangays'));

                fetch(geoUrl)
                .then(r => r.json())
                .then(osm => {
                    const geo = osmtogeojson(osm);
                    if (!geo || !geo.features || geo.features.length === 0) {
                        throw new Error('No features after conversion');
                    }

                    const barangayFeatures = geo.features.filter(f =>
                        f.properties && (f.properties.tags?.admin_level === '10' || f.properties.tags?.place === 'barangay')
                    );
                    const municipalityFeatures = geo.features.filter(f =>
                        f.properties && f.properties.tags?.admin_level === '8' && f.properties.tags?.name === 'Dulag'
                    );

                    // Draw municipality boundary (Dulag only)
                    let dulagBounds = null;
                    if (municipalityFeatures.length > 0) {
                        const dulagLayer = L.geoJSON(municipalityFeatures, {
                            style: { color: '#1d4ed8', weight: 2, fill: false, opacity: 0.9 }
                        }).addTo(map);
                        dulagBounds = dulagLayer.getBounds();
                        map.fitBounds(dulagBounds.pad(0.05));
                        map.setMaxBounds(dulagBounds.pad(0.25));
                    }

                    // Draw barangay polygons (clickable)
                    const brgyLayer = L.geoJSON(barangayFeatures, {
                        style: { color: '#2563eb', weight: 1, fillColor: '#60a5fa', fillOpacity: 0.18 },
                        onEachFeature: (feature, layer) => {
                            const name = feature?.properties?.tags?.name;
                            if (!name) return;
                            const count = counts[name] ?? 0;
                            layer.bindTooltip(`${name} (${count})`, { sticky: true });
                            layer.on('click', () => {
                                window.location.href = baseUrl + '?barangay=' + encodeURIComponent(name);
                            });
                        }
                    }).addTo(map);

                    if (!dulagBounds && brgyLayer.getBounds && brgyLayer.getBounds().isValid()) {
                        map.fitBounds(brgyLayer.getBounds().pad(0.05));
                    }

                    if (statusEl) {
                        statusEl.textContent = 'Loaded Dulag boundary and barangays. Click a barangay area to view records.';
                    }
                })
                .catch(err => {
                    console.error(err);
                    if (statusEl) {
                        statusEl.textContent = 'Map loaded, but barangay boundaries could not be fetched. Use the barangay buttons below.';
                    }
                });
            })();
            </script>
            @endpush

            @if(isset($selected) && $seniorCitizens)
                <x-modal name="barangayModal" :show="isset($selected)" focusable>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Residents of {{ $selected }}</h3>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('reports.barangay') }}" class="px-3 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded">Close</a>
                            </div>
                        </div>

                        <!-- Unified Filters -->
                        <x-filter-bar
                            :action="route('reports.barangay', ['barangay' => $selected])"
                            :resetUrl="route('reports.barangay', ['barangay' => $selected])"
                            :hasActiveFilters="request()->filled('search') || request()->filled('sex') || request()->filled('age_range') || request()->filled('age_exact')"
                            :activeCount="(request()->filled('search') ? 1 : 0) + (request()->filled('sex') ? 1 : 0) + (request()->filled('age_range') || request()->filled('age_exact') ? 1 : 0) + (request('sort') && request('sort') !== 'name_asc' ? 1 : 0)"
                        >
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Search</label>
                                <input type="text" name="search" placeholder="Search name or OSCA ID" value="{{ request('search') }}" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Sex</label>
                                <select name="sex" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                                    <option value="">All</option>
                                    <option value="Male" {{ request('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div>
                                <x-age-range-filter name="age_range" :value="request('age_range')" />
                            </div>
                            <div>
                                <x-sort-dropdown />
                            </div>
                            <div class="flex items-end">
                                <a href="{{ route('reports.barangay.export', request()->query()) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-gray-700 dark:bg-gray-800 text-white text-xs md:text-sm font-medium rounded-md hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                                    📥 Export CSV
                                </a>
                            </div>
                        </x-filter-bar>

                        @if($seniorCitizens->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400">No records found in this barangay.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-900 dark:text-gray-100">
                                    <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <tr>
                                            <th class="px-6 py-3 font-semibold">{{ __('Full Name') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('Age') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('Sex') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('OSCA ID') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('Contact') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($seniorCitizens as $citizen)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 font-medium">{{ $citizen->getFormattedDisplayName() }}</td>
                                                <td class="px-6 py-4">{{ $citizen->age }}</td>
                                                <td class="px-6 py-4">{{ $citizen->sex }}</td>
                                                <td class="px-6 py-4">{{ $citizen->osca_id }}</td>
                                                <td class="px-6 py-4">{{ $citizen->contact_number ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 flex gap-2">
                                                    <a href="{{ route('senior-citizens.show', $citizen) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('View') }}</a>
                                                    <a href="{{ route('senior-citizens.edit', $citizen) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline">{{ __('Edit') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                                {{ $seniorCitizens->links() }}
                            </div>
                        @endif
                    </div>
                </x-modal>
            @endif
        </div>
    </div>
</x-app-layout>