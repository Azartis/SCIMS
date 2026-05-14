<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">Import Senior Citizens</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Admin-only bulk import using CSV or Excel.</p>
            </div>
            <a href="{{ route('senior-citizens.index') }}" class="text-sm text-blue-600 dark:text-blue-400 font-medium hover:underline">
                ← Back to Masterlist
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-emerald-50 dark:bg-emerald-900/20 p-3 text-sm text-emerald-700 dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/20 p-3 text-sm text-red-700 dark:text-red-300">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 space-y-5">
                <div class="space-y-1">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Upload file</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Accepted formats: <strong>CSV</strong> or <strong>Excel (.xlsx)</strong>. Maximum size: 5 MB.
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Required columns (heading row): <code>osca_id, lastname, firstname, middlename, sex, barangay, address, contact_number, date_of_birth</code>.
                    </p>
                </div>

                <form method="POST" action="{{ route('imports.seniors.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">Data file</label>
                        <input type="file" name="file" required
                            class="block w-full text-sm text-slate-700 dark:text-slate-100 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-300">
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 dark:bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 dark:hover:bg-blue-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M4 12l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                            Start Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

