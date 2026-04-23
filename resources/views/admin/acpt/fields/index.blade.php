<x-cms-dashboard::layouts.admin>
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <h1 class="text-[23px] font-normal text-[#1d2327]">Field Groups</h1>
            <a href="{{ route('admin.acpt.fields.create') }}" class="wp-btn-secondary px-2 py-0.5 text-[13px]">Add New Group</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-white border-l-4 border-[#46b450] shadow-sm p-3 mb-4 text-[13px]">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-[#c3c4c7] shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-[#c3c4c7]">
                    <th class="wp-table-header w-10"><input type="checkbox" class="rounded-sm border-[#8c8f94]"></th>
                    <th class="wp-table-header">Title</th>
                    <th class="wp-table-header">Fields</th>
                    <th class="wp-table-header">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fieldGroups as $group)
                <tr class="hover:bg-[#f6f7f7] group">
                    <td class="wp-table-cell"><input type="checkbox" class="rounded-sm border-[#8c8f94]"></td>
                    <td class="wp-table-cell">
                        <div class="font-semibold text-[#2271b1] text-[14px] mb-1">
                            <a href="{{ route('admin.acpt.fields.edit', $group) }}" class="hover:text-[#135e96]">{{ $group->title }}</a>
                        </div>
                        <div class="flex gap-2 text-[12px] opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.acpt.fields.edit', $group) }}" class="text-[#2271b1] hover:text-[#135e96]">Edit</a>
                            <span class="text-[#c3c4c7]">|</span>
                            <form action="{{ route('admin.acpt.fields.destroy', $group) }}" method="POST" class="inline" onsubmit="return confirm('Delete this field group permanently?')">
                                @csrf @method('DELETE')
                                <button class="text-[#b32d2e] hover:text-[#d63638]">Delete</button>
                            </form>
                        </div>
                    </td>
                    <td class="wp-table-cell">{{ $group->fields_count }}</td>
                    <td class="wp-table-cell">
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-medium {{ $group->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $group->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="wp-table-cell text-center py-10 text-[#646970] italic">
                        No field groups found. <a href="{{ route('admin.acpt.fields.create') }}" class="text-[#2271b1] underline">Create your first group</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-cms-dashboard::layouts.admin>
