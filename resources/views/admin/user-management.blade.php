<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">User Management</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage system users & roles</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 dark:bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add User
                </a>
                <a href="{{ route('users.export') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-green-600 dark:bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33A3 3 0 0116.5 19.5H6.75z" /></svg>
                    Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">

            {{-- Search and Filter --}}
            <form method="GET" class="mb-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." class="w-full px-2.5 py-1.5 text-xs md:text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Role</label>
                        <select name="role" class="w-full px-2.5 py-1.5 text-xs md:text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-3 py-1.5 bg-blue-600 dark:bg-blue-600 text-white text-xs md:text-sm font-semibold rounded-md hover:bg-blue-700 transition">
                            Filter
                        </button>
                        <a href="{{ route('admin.users') }}" class="flex-1 px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs md:text-sm font-semibold rounded-md hover:bg-slate-200 dark:hover:bg-slate-600 transition text-center">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- Users Table --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/30">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wide">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wide">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wide">Role</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wide">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex items-center gap-1.5 text-green-600 dark:text-green-400">
                                            <span class="w-2 h-2 bg-green-600 dark:bg-green-400 rounded-full"></span>
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-md hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                Edit
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-medium rounded-md hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 text-xs font-medium rounded-md cursor-not-allowed" title="You cannot delete your own account">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    Delete
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                        No users found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($users->hasPages())
                    <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between text-sm">
                        <p class="text-slate-600 dark:text-slate-400">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                        </p>
                        <div class="flex items-center gap-1">
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>
