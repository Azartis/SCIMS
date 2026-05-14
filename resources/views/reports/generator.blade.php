<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">Report Generator</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Choose a report type and export format.</p>
            </div>
            <a href="{{ route('reports.index') }}" class="text-sm text-blue-600 dark:text-blue-400 font-medium hover:underline">
                ← Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/20 p-3 text-sm text-red-700 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 space-y-6">
                <form method="GET" action="{{ route('reports.generate') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Report Type</label>
                            <select name="report_type" class="w-full rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="health">Age Distribution</option>
                                <option value="barangay">Barangay Distribution</option>
                                <option value="classification">Classification Distribution</option>
                                <option value="pension">Pension Status</option>
                                <option value="deceased">Deceased (Last 12 Months)</option>
                                <option value="disability">Disability Distribution</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Format</label>
                            <select name="format" class="w-full rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="csv">CSV</option>
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        CSV and Excel exports are best for spreadsheets, while PDF is ideal for sharing or printing.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 dark:bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 dark:hover:bg-blue-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Generate & Download
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

