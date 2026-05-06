<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Activity Logs - Lazy CMS</x-slot>

    <div class="px-2">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-[23px] font-normal text-[#1d2327]">Activity Logs</h1>
        </div>

        @include('cms-dashboard::admin.settings.nav')

        <div class="flex flex-wrap justify-between items-center mb-2 gap-2">
            <div class="flex items-center gap-1">
                <form action="{{ route('admin.settings.activity-logs') }}" method="GET" class="flex items-center gap-2">
                    <select name="user_id" class="h-8 text-[13px] border-[#8c8f94] rounded shadow-sm" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>

                    <select name="action" class="h-8 text-[13px] border-[#8c8f94] rounded shadow-sm" onchange="this.form.submit()">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="settings_updated" {{ request('action') == 'settings_updated' ? 'selected' : '' }}>Settings Updated</option>
                    </select>
                </form>
            </div>

            <div class="flex items-center gap-2">
                <form action="{{ route('admin.settings.activity-logs') }}" method="GET" class="flex gap-1">
                    @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                    @if(request('action')) <input type="hidden" name="action" value="{{ request('action') }}"> @endif
                    <input type="search" name="s" value="{{ request('s') }}" class="wp-input h-8 px-2 border border-[#8c8f94] focus:border-[#2271b1] outline-none" placeholder="Search Logs...">
                    <button type="submit" class="border border-[#8c8f94] text-[#2c3338] px-3 py-1 rounded-[3px] text-[13px] font-semibold hover:bg-[#f6f7f7]">Search Logs</button>
                    @if(request()->hasAny(['user_id', 'action', 's']))
                        <a href="{{ route('admin.settings.activity-logs') }}" class="text-[13px] text-[#2271b1] hover:text-[#135e96] flex items-center ml-2">Clear</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="flex justify-end mb-2">
            <x-cms-dashboard::admin.pagination :paginator="$logs" size="small" />
        </div>

        <div class="bg-white border border-[#c3c4c7] shadow-sm">
            <table class="wp-list-table w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-[#c3c4c7]">
                        <th class="p-3 text-[14px] font-bold text-[#2c3338] w-[150px]">Date</th>
                        <th class="p-3 text-[14px] font-bold text-[#2c3338] w-[150px]">User</th>
                        <th class="p-3 text-[14px] font-bold text-[#2c3338] w-[120px]">Action</th>
                        <th class="p-3 text-[14px] font-bold text-[#2c3338]">Description</th>
                        <th class="p-3 text-[14px] font-bold text-[#2c3338] w-[180px]">Location & IP</th>
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
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-[#2271b1] text-white flex items-center justify-center text-[11px] font-bold">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-medium block">{{ $log->user->name ?? 'System' }}</span>
                                        <span class="text-[10px] text-[#646970]">{{ $log->user->email ?? '' }}</span>
                                    </div>
                                </div>
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
                                <div class="flex flex-col gap-1">
                                    @if($log->country_code)
                                        @php
                                            $flag = collect(str_split(strtoupper($log->country_code)))
                                                ->map(fn($char) => mb_chr(ord($char) + 127397))
                                                ->implode('');
                                        @endphp
                                        <div class="flex items-center gap-1.5" title="{{ $log->country }}">
                                            <span class="text-[16px]">{{ $flag }}</span>
                                            <span class="text-[12px] font-medium">{{ $log->country }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 text-[#646970]">
                                            <span class="material-symbols-outlined text-[16px]">public</span>
                                            <span class="text-[12px]">Local/Unknown</span>
                                        </div>
                                    @endif
                                    <code class="text-[11px] bg-[#f0f0f1] px-1 py-0.5 rounded w-fit">{{ $log->ip_address }}</code>
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

            <div class="p-4 border-t border-[#c3c4c7] bg-[#f6f7f7] flex justify-between items-center">
                <span class="text-[13px] text-[#2c3338]">{{ $logs->total() }} items</span>
                <x-cms-dashboard::admin.pagination :paginator="$logs" />
            </div>
        </div>
    </div>
</x-cms-dashboard::layouts.admin>
