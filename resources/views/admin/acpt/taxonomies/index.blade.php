<x-cms-dashboard::layouts.admin title="Taxonomies">
    <div class="max-w-[1200px] mx-auto pb-12 mt-2">
        <div class="flex items-center mb-4">
            <h1 class="text-[22px] font-normal text-[#1d2327] mr-3">Taxonomies</h1>
            <a href="{{ route('admin.acpt.taxonomies.create') }}" class="wp-btn-outline font-normal px-2.5 py-0.5 border-[#2271b1]">Add New</a>
        </div>

        @if(session('success'))
            <div class="bg-white border-l-4 border-[#00a32a] p-3 mb-4 shadow-[0_1px_1px_rgba(0,0,0,0.04)] text-[13px] text-[#1d2327]">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex justify-between items-center mb-2">
            <div class="text-[13px] text-[#646970]">
                <a href="?" class="text-[#2271b1] font-semibold">All <span class="text-[#646970]">({{ $taxonomies->count() }})</span></a> |
                <a href="?status=active" class="hover:text-[#2271b1]">Active <span class="text-[#646970]">({{ $taxonomies->where('is_active', true)->count() }})</span></a>
            </div>
            <div class="flex items-center">
                <form action="{{ route('admin.acpt.taxonomies.index') }}" method="GET" class="flex m-0">
                    <input type="text" name="s" value="{{ request('s') }}" class="wp-input h-[28px] mr-1" placeholder="">
                    <button type="submit" class="wp-btn-secondary h-[28px] leading-none px-3 border-[#8c8f94] text-[#2c3338]">Search Taxonomies</button>
                </form>
            </div>
        </div>

        <form action="{{ route('admin.acpt.taxonomies.bulk') }}" method="POST" id="bulk-action-form">
            @csrf

            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center space-x-1">
                    <select name="action" class="wp-input h-[28px] py-0 text-[13px] pr-8">
                        <option value="none">Bulk actions</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="trash">Move to Trash</option>
                    </select>
                    <button type="submit" class="wp-btn-secondary h-[28px] leading-none border-[#8c8f94] text-[#2c3338]">Apply</button>
                </div>
                <div class="text-[13px] text-[#646970]">
                    <x-cms-dashboard::admin.pagination :paginator="$taxonomies" />
                </div>
            </div>

            <div class="bg-white border border-[#c3c4c7] shadow-[0_1px_1px_rgba(0,0,0,0.04)] overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="w-8 border-b border-[#c3c4c7] p-2 pl-3">
                                <input type="checkbox" id="cb-select-all-1" class="border-[#8c8f94] rounded-[2px] h-4 w-4" onclick="document.querySelectorAll('.cb-select').forEach(cb => cb.checked = this.checked)">
                            </th>
                            <th class="wp-table-header font-semibold text-[#2271b1]">Title <span class="text-[#8c8f94] text-[10px]">▼</span></th>
                            <th class="wp-table-header font-semibold">Description</th>
                            <th class="wp-table-header font-semibold">Post Types</th>
                            <th class="wp-table-header font-semibold">Field Groups</th>
                            <th class="wp-table-header font-semibold w-24">Terms</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0f0f1]">
                        @forelse($taxonomies as $taxonomy)
                            <tr class="group bg-white hover:bg-[#f6f7f7]">
                                <td class="p-2 pl-3">
                                    <input type="checkbox" name="taxonomies[]" value="{{ $taxonomy->id }}" class="cb-select border-[#8c8f94] rounded-[2px] h-4 w-4">
                                </td>
                                <td class="wp-table-cell text-[#2271b1] font-semibold text-[13px] py-2">
                                    <a href="{{ route('admin.acpt.taxonomies.edit', $taxonomy->id) }}" class="hover:underline">{{ $taxonomy->name }} {!! !$taxonomy->is_active ? '<span class="text-[#d63638] text-[11px] font-normal">(Inactive)</span>' : '' !!}</a>
                                    <div class="invisible group-hover:visible mt-1 text-[13px] font-normal space-x-1 text-[#646970] flex items-center">
                                        <a href="{{ route('admin.acpt.taxonomies.edit', $taxonomy->id) }}" class="text-[#2271b1] hover:underline">Edit</a>
                                        <span class="text-[#c3c4c7]">|</span>
                                        <form action="{{ route('admin.acpt.taxonomies.bulk') }}" method="POST" class="inline m-0 p-0 leading-none">
                                            @csrf
                                            <input type="hidden" name="action" value="trash">
                                            <input type="hidden" name="taxonomies[]" value="{{ $taxonomy->id }}">
                                            <button type="submit" class="text-[#d63638] hover:underline bg-transparent border-0 cursor-pointer p-0 leading-none">Trash</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="wp-table-cell text-[#646970]">{{ $taxonomy->description ?? '—' }}</td>
                                <td class="wp-table-cell text-[#646970]">
                                    @if(is_array($taxonomy->post_types) && count($taxonomy->post_types) > 0)
                                        {{ implode(', ', array_map('ucfirst', $taxonomy->post_types)) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="wp-table-cell text-[#646970]">—</td>
                                <td class="wp-table-cell text-[#2271b1] font-semibold">
                                    <a href="{{ route('admin.acpt.terms.index', $taxonomy->slug) }}" class="hover:underline">
                                        {{ $taxonomy->terms()->count() }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="wp-table-cell text-center p-3 text-[#646970]">No taxonomies found. <a href="{{ route('admin.acpt.taxonomies.create') }}" class="text-[#2271b1] hover:underline">Create one</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border-t border-[#c3c4c7] p-2 pl-3">
                                <input type="checkbox" id="cb-select-all-2" class="border-[#8c8f94] rounded-[2px] h-4 w-4" onclick="document.querySelectorAll('.cb-select').forEach(cb => cb.checked = this.checked)">
                            </th>
                            <th class="wp-table-header font-semibold border-t text-[#2271b1]">Title <span class="text-[#8c8f94] text-[10px]">▲</span></th>
                            <th class="wp-table-header font-semibold border-t">Description</th>
                            <th class="wp-table-header font-semibold border-t">Post Types</th>
                            <th class="wp-table-header font-semibold border-t">Field Groups</th>
                            <th class="wp-table-header font-semibold border-t w-24">Terms</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex justify-between items-center mt-2">
                <div class="flex items-center space-x-1">
                    <select name="action2" class="wp-input h-[28px] py-0 text-[13px] pr-8">
                        <option value="none">Bulk actions</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="trash">Move to Trash</option>
                    </select>
                    <button type="submit" class="wp-btn-secondary h-[28px] leading-none border-[#8c8f94] text-[#2c3338]">Apply</button>
                </div>
                <div class="text-[13px] text-[#646970]">
                    {{ $taxonomies->count() }} items
                </div>
            </div>
        </form>

    </div>
</x-cms-dashboard::layouts.admin>
