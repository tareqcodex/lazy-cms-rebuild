<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Activity Logs - Lazy CMS</x-slot>

    <div class="px-2">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-[23px] font-normal text-[#1d2327]">Activity Logs</h1>
        </div>

        <div class="bg-white border border-[#c3c4c7] shadow-sm">
            <div class="p-4 border-b border-[#c3c4c7] flex flex-wrap items-center gap-4 bg-[#f6f7f7]">
                <form action="{{ route('admin.settings.activity-logs') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="user_id" class="h-8 text-[13px] border-[#8c8f94] rounded shadow-sm">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>

                    <select name="action" class="h-8 text-[13px] border-[#8c8f94] rounded shadow-sm">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="settings_updated" {{ request('action') == 'settings_updated' ? 'selected' : '' }}>Settings Updated</option>
                    </select>

                    <button type="submit" class="wp-btn-secondary h-8 px-3">Filter</button>
                    @if(request()->hasAny(['user_id', 'action']))
                        <a href="{{ route('admin.settings.activity-logs') }}" class="text-[13px] text-[#2271b1] hover:text-[#135e96] ml-2">Clear</a>
                    @endif
                </form>
            </div>

            <table class="wp-list-table w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-[#c3c4c7]">
                        <th class="p-3 text-[14px] font-bold text-[#2c3338] w-[150px]">Date</th>
                        <th class="p-3 text-[14px] font-bold text-[#2c3338] w-[120px]">User</th>
                        <th class="p-3 text-[14px] font-bold text-[#2c3338] w-[120px]">Action</th>
                        <th class="p-3 text-[14px] font-bold text-[#2c3338]">Description</th>
                        <th class="p-3 text-[14px] font-bold text-[#2c3338] w-[150px]">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f0f1]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#f6f7f7] transition-colors">
                            <td class="p-3 text-[13px] text-[#2c3338] align-top">
                                {{ $log->created_at->format('M d, Y') }}<br>
                                <span class="text-[11px] text-[#646970]">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>
                            <td class="p-3 text-[13px] text-[#2c3338] align-top">
                                <span class="font-medium">{{ $log->user->name ?? 'System' }}</span>
                            </td>
                            <td class="p-3 text-[13px] align-top">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider
                                    {{ $log->action === 'created' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $log->action === 'updated' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $log->action === 'deleted' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $log->action === 'settings_updated' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ !in_array($log->action, ['created', 'updated', 'deleted', 'settings_updated']) ? 'bg-gray-100 text-gray-700' : '' }}
                                ">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="p-3 text-[13px] text-[#2c3338] align-top">
                                <p class="mb-1">{{ $log->description }}</p>
                                @if(!empty($log->model_type))
                                    <span class="text-[11px] text-[#646970] italic">
                                        {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-[13px] text-[#2c3338] align-top">
                                <code class="text-[11px] bg-[#f0f0f1] px-1 py-0.5 rounded">{{ $log->ip_address }}</code>
                                <div class="mt-1 text-[10px] text-[#646970] line-clamp-1" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 30) }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[#646970] italic">No activity logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($logs->hasPages())
                <div class="p-4 border-t border-[#c3c4c7] bg-[#f6f7f7]">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-cms-dashboard::layouts.admin>
