<div class="space-y-6">

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif


    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Users Management</h2>
            <p class="text-gray-600 mt-1">Manage all users in the system</p>
        </div>
    </div>


    {{-- Filters & Search --}}
    <div class="bg-white rounded-lg shadow p-4">

        {{-- <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Search
                </label>

                <div class="relative">
                    <input
                        type="text"
                        wire:model.debounce.300ms="search"
                        placeholder="Search by name, email, or username..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg
                               focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >

                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                        </path>
                    </svg>
                </div>
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>

                <select
                    wire:model="statusFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Role
                </label>

                <select
                    wire:model="roleFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                    <option value="">All Roles</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

        </div> --}}


        {{-- Bulk Actions --}}
        {{-- @if(count($selected) > 0)

            <div class="mt-4 flex items-center gap-4">

                <span class="text-sm text-gray-600">
                    {{ count($selected) }} selected
                </span>

                <button
                    wire:click="bulkSuspend"
                    onclick="return confirm('Suspend selected users?')"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg
                           hover:bg-red-700 transition"
                >
                    Suspend Selected
                </button>

            </div>

        @endif --}}

    </div>


    {{-- Users Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">


                {{-- Table Head --}}
                <thead class="bg-gray-50">

                    <tr>

                        {{-- <th class="px-6 py-3 text-left">

                            <input
                                type="checkbox"
                                wire:model="selectAll"
                                class="rounded border-gray-300 text-indigo-600
                                       focus:ring-indigo-500"
                            >

                        </th> --}}

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            User
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Role
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Premium
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Last Login
                        </th>

                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Actions
                        </th> --}}

                    </tr>

                </thead>


                {{-- Table Body --}}
                <tbody class="bg-white divide-y divide-gray-200">


                    @forelse($users as $user)

                        <tr class="hover:bg-gray-50">


                            {{-- Checkbox --}}
                            {{-- <td class="px-6 py-4">

                                <input
                                    type="checkbox"
                                    wire:model="selected"
                                    value="{{ $user->id }}"
                                    class="rounded border-gray-300 text-indigo-600
                                           focus:ring-indigo-500"
                                >

                            </td> --}}


                            {{-- User --}}
                            <td class="px-6 py-4 whitespace-nowrap">

                                <div class="flex items-center">

                                    <div
                                        class="h-10 w-10 bg-indigo-100 rounded-full
                                               flex items-center justify-center"
                                    >
                                        <span class="text-indigo-600 font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $user->email }}
                                        </div>
                                    </div>

                                </div>

                            </td>


                            {{-- Role --}}
                            <td class="px-6 py-4">

                                <span
                                    class="px-2 text-xs font-semibold rounded-full
                                    {{ $user->role === 'admin'
                                        ? 'bg-purple-100 text-purple-800'
                                        : 'bg-gray-100 text-gray-800' }}"
                                >
                                    {{ ucfirst($user->role) }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4">

                                <span
                                    class="px-2 text-xs font-semibold rounded-full
                                    {{ $user->status === 'active'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800' }}"
                                >
                                    {{ ucfirst($user->status) }}
                                </span>

                            </td>


                            {{-- Premium --}}
                            <td class="px-6 py-4 text-sm text-gray-500">

                                @if($user->is_premium)

                                    Yes

                                @else
                                    No
                                @endif

                            </td>


                            {{-- Last Login --}}
                            <td class="px-6 py-4 text-sm text-gray-500">

                                {{ $user->last_login_at
                                    ? $user->last_login_at->diffForHumans()
                                    : 'Never'
                                }}

                            </td>


                            {{-- Actions --}}
                            {{-- <td class="px-6 py-4 text-sm font-medium space-x-2">

                                @if($user->role !== 'admin')

                                    <button
                                        wire:click="suspendUser('{{ $user->id }}')"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>


                                    <button
                                        wire:click="deleteUser('{{ $user->id }}')"
                                        onclick="return confirm('Delete this user?')"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Delete
                                    </button>

                                @else

                                    <span class="text-gray-400">Protected</span>

                                @endif

                            </td> --}}


                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No users found
                            </td>
                        </tr>

                    @endforelse


                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>

    </div>

</div>
