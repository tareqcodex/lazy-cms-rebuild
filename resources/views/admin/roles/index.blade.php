<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Roles - Lazy CMS</x-slot>

    <div class="px-2">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-[23px] font-normal text-[#1d2327]">Roles</h1>
            <a href="{{ route('admin.roles.create') }}" class="px-2 py-1 border border-[#2271b1] text-[#2271b1] hover:bg-[#f0f6fa] rounded-[3px] text-[13px] font-semibold">Add New Role</a>
        </div>

        @if(session('success'))
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-4 text-[13px] text-[#1d2327]">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-[#ccd0d4] shadow-sm">
            <table class="w-full text-[13px] text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#ccd0d4] bg-[#f9f9f9]">
                        <th class="p-2 font-semibold text-[#2c3338]">Name</th>
                        <th class="p-2 font-semibold text-[#2c3338]">Slug</th>
                        <th class="p-2 font-semibold text-[#2c3338]">Description</th>
                        <th class="p-2 font-semibold text-[#2c3338]">Users</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr class="border-b border-[#f0f0f1] hover:bg-[#f6f7f7] group">
                            <td class="p-2">
                                <div class="font-semibold text-[#2271b1]">{{ $role->name }}</div>
                                <div class="opacity-0 group-hover:opacity-100 flex gap-2 mt-1 text-[12px]">
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-[#2271b1] hover:text-[#135e96]">Edit</a>
                                    @if(!in_array($role->slug, ['administrator', 'subscriber']))
                                        <span class="text-[#dcdcde]">|</span>
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[#b32d2e] hover:text-[#8a2424]" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <td class="p-2 text-[#2c3338]">{{ $role->slug }}</td>
                            <td class="p-2 text-[#646970]">{{ $role->description }}</td>
                            <td class="p-2 text-[#2271b1]">{{ $role->users_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-cms-dashboard::layouts.admin>
