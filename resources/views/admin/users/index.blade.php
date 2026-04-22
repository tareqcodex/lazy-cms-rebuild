<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Users - Lazy CMS</x-slot>

    <div class="px-2">
        <div class="flex items-baseline gap-2 mb-6">
            <h1 class="text-[23px] font-normal text-[#1d2327]">Users</h1>
            <a href="{{ route('admin.users.create') }}" class="border border-[#2271b1] text-[#2271b1] px-2 py-0.5 rounded-[3px] text-[13px] font-semibold hover:bg-[#f0f6fa]">Add New</a>
        </div>

        @if(session('success'))
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-6 text-[13px] text-[#1d2327]">
                {{ session('success') }}
            </div>
        @endif

        <div class="tabs-nav mb-4">
            <ul class="flex gap-2 text-[13px] text-[#1d2327] border-slate-200">
                <li class="pr-2 border-r border-slate-300"><a href="{{ route('admin.users.index') }}" class="{{ !request('role') ? 'font-bold' : 'text-[#2271b1]' }}">All <span class="text-[#646970] font-normal">({{ $allCount }})</span></a></li>
                <li class="px-2 border-r border-slate-300"><a href="{{ route('admin.users.index', ['role' => 'administrator']) }}" class="{{ request('role') == 'administrator' ? 'font-bold' : 'text-[#2271b1]' }}">Administrator <span class="text-[#646970] font-normal">({{ $adminCount }})</span></a></li>
                <li class="px-2 border-r border-slate-300"><a href="{{ route('admin.users.index', ['role' => 'editor']) }}" class="{{ request('role') == 'editor' ? 'font-bold' : 'text-[#2271b1]' }}">Editor <span class="text-[#646970] font-normal">({{ $editorCount }})</span></a></li>
                <li class="px-2 border-r border-slate-300"><a href="{{ route('admin.users.index', ['role' => 'author']) }}" class="{{ request('role') == 'author' ? 'font-bold' : 'text-[#2271b1]' }}">Author <span class="text-[#646970] font-normal">({{ $authorCount }})</span></a></li>
                <li class="px-2 border-r border-slate-300"><a href="{{ route('admin.users.index', ['role' => 'subscriber']) }}" class="{{ request('role') == 'subscriber' ? 'font-bold' : 'text-[#2271b1]' }}">Subscriber <span class="text-[#646970] font-normal">({{ $subscriberCount }})</span></a></li>
                <li class="pl-2"><a href="{{ route('admin.users.index', ['status' => 'blocked']) }}" class="{{ request('status') == 'blocked' ? 'font-bold' : 'text-[#2271b1]' }}">Blocked <span class="text-[#f87171] font-normal">({{ $blockedCount }})</span></a></li>
            </ul>
        </div>

        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-1">
                <select class="wp-input h-8 text-[13px]">
                    <option>Bulk Actions</option>
                    <option>Delete</option>
                </select>
                <button class="border border-[#2271b1] text-[#2271b1] px-3 py-1 rounded-[3px] text-[13px] font-semibold hover:bg-[#f0f6fa]">Apply</button>
            </div>

            <form action="" method="GET" class="flex gap-1">
                <input type="search" name="s" value="{{ request('s') }}" class="wp-input h-8 px-2 border border-[#8c8f94] focus:border-[#2271b1] outline-none">
                <button type="submit" class="border border-[#8c8f94] text-[#2c3338] px-3 py-1 rounded-[3px] text-[13px] font-semibold hover:bg-[#f6f7f7]">Search Users</button>
            </form>
        </div>

        <div class="bg-white border border-[#c3c4c7] shadow-sm">
            <table class="w-full text-left text-[13px] border-collapse">
                <thead>
                    <tr class="border-b border-[#c3c4c7] bg-[#f9f9f9]">
                        <th class="p-2 w-10 text-center"><input type="checkbox"></th>
                        <th class="p-2 font-semibold">Username</th>
                        <th class="p-2 font-semibold">Name</th>
                        <th class="p-2 font-semibold">Email</th>
                        <th class="p-2 font-semibold">Role</th>
                        <th class="p-2 font-semibold">Posts</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-[#f0f0f1] hover:bg-[#f6f7f7] group">
                            <td class="p-2 text-center"><input type="checkbox"></td>
                            <td class="p-2 font-semibold">
                                <div class="flex items-center gap-2">
                                    <img src="https://secure.gravatar.com/avatar/{{ md5($user->email) }}?s=32&d=mm" class="w-8 h-8 rounded">
                                    <div>
                                        @php 
                                            $isBlocked = $user->is_blocked || ($user->blocked_until && $user->blocked_until->isFuture());
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-[#2271b1] font-semibold">{{ $user->username ?: $user->name }}</a>
                                            @if($isBlocked)
                                                <span class="bg-[#f87171] text-white text-[10px] px-1.5 py-0.5 rounded font-bold uppercase">Blocked</span>
                                            @endif
                                        </div>
                                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 text-[12px] mt-1 pr-4 items-center">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-[#2271b1]">Edit</a>
                                            <span class="text-[#c3c4c7]">|</span>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-[#b32d2e] {{ auth()->id() == $user->id ? 'hidden' : '' }}">Delete</button>
                                            </form>
                                            <span class="text-[#c3c4c7] {{ auth()->id() == $user->id ? 'hidden' : '' }}">|</span>
                                            
                                            <form action="{{ route('admin.users.toggle-block', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="{{ $isBlocked ? 'text-[#00a32a]' : 'text-[#b32d2e]' }} {{ auth()->id() == $user->id ? 'hidden' : '' }}">
                                                    {{ $isBlocked ? 'Unblock' : 'Block' }}
                                                </button>
                                            </form>
                                            <span class="text-[#c3c4c7] {{ auth()->id() == $user->id ? 'hidden' : '' }}">|</span>
                                            <a href="#" class="text-[#2271b1]">View</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2">{{ $user->name }}</td>
                            <td class="p-2 "><a href="mailto:{{ $user->email }}" class="text-[#2271b1]">{{ $user->email }}</a></td>
                            <td class="p-2 text-[#2c3338]">{{ $user->role ? $user->role->name : 'No Role' }}</td>
                            <td class="p-2 text-[#2271b1]">0</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-slate-500 italic">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between items-center text-[13px] text-[#2c3338]">
            <div>{{ $users->total() }} items</div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-cms-dashboard::layouts.admin>
