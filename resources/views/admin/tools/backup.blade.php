<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Backup & Snapshots - Lazy CMS</x-slot>

    <div class="px-2">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-[23px] font-normal text-[#1d2327]">Backup & Snapshots</h1>
            <form action="{{ route('admin.backup.create') }}" method="POST">
                @csrf
                <button type="submit" class="wp-btn-primary">
                    <span class="material-symbols-outlined mr-1">backup</span>
                    Create New Snapshot
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-6 text-[13px] text-[#1d2327]">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-[#fcf0f1] border-l-4 border-[#d63638] p-3 mb-6 text-[13px] text-[#1d2327]">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white border border-[#c3c4c7] shadow-sm">
            <div class="p-4 border-b border-[#c3c4c7] bg-[#f6f7f7]">
                <h2 class="text-[14px] font-semibold text-[#1d2327]">Available Snapshots</h2>
                <p class="text-[12px] text-[#646970]">Full database exports available for download. Keep your data safe.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[13px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#c3c4c7]">
                            <th class="p-3 font-semibold text-[#2c3338]">Filename</th>
                            <th class="p-3 font-semibold text-[#2c3338]">Size</th>
                            <th class="p-3 font-semibold text-[#2c3338]">Created Date</th>
                            <th class="p-3 font-semibold text-[#2c3338] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $backup)
                            <tr class="border-b border-[#f0f0f1] hover:bg-[#f9f9f9]">
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[#646970]">description</span>
                                        <span class="font-medium text-[#2271b1]">{{ $backup['name'] }}</span>
                                    </div>
                                </td>
                                <td class="p-3 text-[#646970]">{{ $backup['size'] }}</td>
                                <td class="p-3 text-[#646970]">{{ $backup['date'] }}</td>
                                <td class="p-3 text-right">
                                    <div class="flex justify-end gap-3">
                                        <form action="{{ route('admin.backup.restore', $backup['name']) }}" method="POST" onsubmit="return confirm('WARNING: This will overwrite your current database. Are you sure you want to restore this snapshot?')">
                                            @csrf
                                            <button type="submit" class="text-[#b16d22] hover:underline flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[18px]">history</span>
                                                Restore
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.backup.download', $backup['name']) }}" class="text-[#2271b1] hover:underline flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[18px]">download</span>
                                            Download
                                        </a>
                                        <form action="{{ route('admin.backup.destroy', $backup['name']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this snapshot?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[#b32d2e] hover:underline flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-[#646970] italic">
                                    No snapshots found. Click "Create New Snapshot" to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 bg-[#f0f6fa] border border-[#d5ecf5] p-4 rounded-sm">
            <h3 class="text-[14px] font-semibold text-[#0c3d5d] mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">info</span>
                Pro Tip: Regular Backups
            </h3>
            <p class="text-[13px] text-[#1d2327] leading-relaxed">
                Database snapshots capture your posts, pages, users, and settings. It is recommended to download your snapshots and store them in a secure offline location. 
                <br>
                <strong>Note:</strong> This tool currently only backs up the database structure and data. Uploaded images and files should be backed up separately from your <code>storage/app/public/uploads</code> directory.
            </p>
        </div>
    </div>
</x-cms-dashboard::layouts.admin>
