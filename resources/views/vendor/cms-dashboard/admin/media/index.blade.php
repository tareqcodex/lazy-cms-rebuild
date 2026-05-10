<x-cms-dashboard::layouts.admin>
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <h1 class="text-[23px] font-normal text-[#1d2327]">Media Library</h1>
            <a href="{{ route('admin.media.create') }}" class="wp-btn-secondary px-2 py-0.5 text-[13px]">Add Media File</a>
        </div>
    </div>

    <!-- Media Toolbar -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4 bg-white p-2 border border-[#c3c4c7] rounded-sm">
        <div class="flex items-center gap-2">
            <!-- View Icons -->
            <div class="flex border-r border-[#c3c4c7] pr-2 mr-2">
                <button class="p-1 text-[#2271b1]"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg></button>
                <button class="p-1 text-[#646970]"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" /></svg></button>
            </div>
            
            <select class="wp-input h-7 text-[13px] py-0 w-36">
                <option>All media items</option>
                <option>Images</option>
            </select>
            <select class="wp-input h-7 text-[13px] py-0 w-32">
                <option>All dates</option>
            </select>
            <button class="wp-btn-secondary h-7 px-3 text-[13px]">Bulk select</button>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="text-[13px] text-[#646970]">Search media items:</span>
            <input type="text" class="wp-input h-7 text-[13px] w-48">
        </div>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-3 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-3 bg-white p-4 border border-[#c3c4c7]">
        @forelse($media as $index => $item)
            <div class="relative cursor-pointer group media-item"
                 data-item='@json($item)' data-index="{{ $index }}">
                <!-- Thumbnail -->
                <div class="aspect-square border-2 border-transparent bg-[#f0f0f1] overflow-hidden group-hover:border-[#2271b1] transition-all">
                    <img src="{{ asset('storage/'.$item->path) }}" class="w-full h-full object-cover">
                </div>
                <!-- Filename Info -->
                <div class="mt-1 px-1">
                    <div class="text-[10px] text-[#1d2327] truncate font-medium" title="{{ $item->filename }}">
                        {{ $item->filename }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center text-[#646970] italic">
                No media files yet. <a href="{{ route('admin.media.create') }}" class="text-[#2271b1] underline">Upload some?</a>
            </div>
        @endforelse
    </div>

    <!-- Attachment Details Modal (WP Full Page Style) -->
    <div id="attachment-details-modal" class="hidden fixed inset-0 z-[100000] overflow-hidden flex flex-col">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="details-modal-backdrop"></div>
        
        <div class="relative m-4 sm:m-10 bg-white shadow-2xl flex-grow flex flex-col overflow-hidden border border-[#c3c4c7]">
            <!-- Header -->
            <div class="h-[50px] border-b border-[#c3c4c7] flex justify-between items-center px-4 bg-white shrink-0">
                <h1 class="text-[20px] font-normal text-[#1d2327]">Attachment details</h1>
                <div class="flex items-center">
                    <button id="prev-attachment" class="p-2 text-[#646970] hover:text-[#2271b1] disabled:opacity-30"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                    <button id="next-attachment" class="p-2 text-[#646970] hover:text-[#2271b1] disabled:opacity-30"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                    <button id="close-details-modal" class="ml-4 p-2 text-[#646970] hover:text-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="flex flex-grow overflow-hidden flex-col md:flex-row">
                <!-- Large Image View -->
                <div class="flex-grow bg-[#f0f0f1] flex items-center justify-center p-8 overflow-auto relative">
                    <img id="modal-detail-img" src="" class="max-w-full max-h-full shadow-lg">
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
                         <button class="wp-btn-secondary h-7">Edit Image</button>
                    </div>
                </div>

                <!-- Sidebar Details -->
                <div class="w-full md:w-[350px] border-l border-[#c3c4c7] bg-white p-4 overflow-y-auto overflow-x-hidden">
                    <div class="text-[12px] text-[#2c3338] mb-4 pb-4 border-b border-[#c3c4c7] leading-relaxed space-y-1">
                        <div><strong>Uploaded on:</strong> <span id="modal-detail-date" class="text-[#646970]"></span></div>
                        <div class="truncate"><strong>File name:</strong> <span id="modal-detail-filename" class="text-[#646970]"></span></div>
                        <div><strong>File type:</strong> <span id="modal-detail-mime" class="text-[#646970]"></span></div>
                        <div class="pt-2 border-t border-[#f0f0f1] mt-2">
                            <div><strong>Main File size:</strong> <span id="modal-detail-orig-size" class="text-[#646970]"></span></div>
                            <div><strong>Compression Size:</strong> <span id="modal-detail-comp-size" class="text-[#646970]"></span></div>
                            <div><strong>Total Compression:</strong> <span id="modal-detail-pct" class="text-green-600 font-bold"></span></div>
                            <div><strong>Status:</strong> <span id="modal-detail-status" class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase"></span></div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div><label class="block text-[12px] font-bold text-[#646970] mb-1">Alternative Text</label><textarea id="modal-meta-alt" class="wp-input w-full text-[13px] h-16"></textarea></div>
                        <div><label class="block text-[12px] font-bold text-[#646970] mb-1">Title</label><input type="text" id="modal-meta-title" class="wp-input w-full text-[13px] h-8"></div>
                        <div><label class="block text-[12px] font-bold text-[#646970] mb-1">Caption</label><textarea id="modal-meta-caption" class="wp-input w-full text-[13px] h-16"></textarea></div>
                        <div><label class="block text-[12px] font-bold text-[#646970] mb-1">Description</label><textarea id="modal-meta-desc" class="wp-input w-full text-[13px] h-16"></textarea></div>
                        <div><label class="block text-[12px] font-bold text-[#646970] mb-1">File URL:</label><div class="flex gap-1"><input type="text" id="modal-meta-url" readonly class="wp-input grow text-[11px] h-8 bg-[#f6f7f7]"><button onclick="document.getElementById('modal-meta-url').select();document.execCommand('copy');alert('Copied!')" class="wp-btn-secondary h-8 px-2 text-[11px]">Copy</button></div></div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-[#c3c4c7] flex flex-wrap gap-4 text-[13px]">
                        <a href="#" id="modal-view-file" target="_blank" class="text-[#2271b1] hover:underline">View media file</a>
                        <button id="modal-delete-btn" class="text-[#b32d2e] hover:underline">Delete permanently</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatBytes(bytes) {
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
            if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
            return bytes + ' B';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const items = Array.from(document.querySelectorAll('.media-item')).map(el => JSON.parse(el.dataset.item));
            const modal = document.getElementById('attachment-details-modal');
            let currentIndex = -1;

            function openDetails(index) {
                currentIndex = index;
                const item = items[index];
                if (!item) return;

                modal.classList.remove('hidden');
                document.getElementById('modal-detail-img').src = `/storage/${item.path}`;
                document.getElementById('modal-detail-filename').innerText = item.filename;
                document.getElementById('modal-detail-mime').innerText = item.mime_type;
                document.getElementById('modal-detail-date').innerText = new Date(item.created_at).toLocaleDateString('en-US', {month:'long', day:'numeric', year:'numeric'});
                
                const orig = item.original_size || 0;
                const comp = item.compressed_size || orig;
                
                document.getElementById('modal-detail-orig-size').innerText = formatBytes(orig);
                document.getElementById('modal-detail-comp-size').innerText = formatBytes(comp);

                const statusEl = document.getElementById('modal-detail-status');
                const pctEl = document.getElementById('modal-detail-pct');

                if (comp < orig) {
                    const saved = orig - comp;
                    const pct = Math.round((saved / orig) * 100);
                    pctEl.innerText = pct + '%';
                    statusEl.innerText = 'Compressed';
                    statusEl.className = 'px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-green-100 text-green-700';
                } else {
                    pctEl.innerText = '0%';
                    statusEl.innerText = 'Uncompressed';
                    statusEl.className = 'px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-700';
                }
                
                document.getElementById('modal-meta-alt').value = item.alt_text || '';
                document.getElementById('modal-meta-title').value = item.title || '';
                document.getElementById('modal-meta-caption').value = item.caption || '';
                document.getElementById('modal-meta-desc').value = item.description || '';
                document.getElementById('modal-meta-url').value = window.location.origin + '/storage/' + item.path;
                document.getElementById('modal-view-file').href = `/storage/${item.path}`;

                document.getElementById('prev-attachment').disabled = (index === 0);
                document.getElementById('next-attachment').disabled = (index === items.length - 1);
            }

            document.querySelectorAll('.media-item').forEach(el => {
                el.addEventListener('click', () => openDetails(parseInt(el.dataset.index)));
            });

            document.getElementById('close-details-modal').addEventListener('click', () => modal.classList.add('hidden'));
            document.getElementById('details-modal-backdrop').addEventListener('click', () => modal.classList.add('hidden'));

            document.getElementById('prev-attachment').addEventListener('click', () => { if(currentIndex > 0) openDetails(currentIndex - 1); });
            document.getElementById('next-attachment').addEventListener('click', () => { if(currentIndex < items.length - 1) openDetails(currentIndex + 1); });

            // Auto-save on blur
            ['alt', 'title', 'caption', 'desc'].forEach(meta => {
                document.getElementById(`modal-meta-${meta}`).addEventListener('blur', function() {
                    if (currentIndex === -1) return;
                    const item = items[currentIndex];
                    const data = {
                        alt_text: document.getElementById('modal-meta-alt').value,
                        title: document.getElementById('modal-meta-title').value,
                        caption: document.getElementById('modal-meta-caption').value,
                        description: document.getElementById('modal-meta-desc').value,
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT'
                    };
                    fetch(`/admin/media/${item.id}`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                        body: JSON.stringify(data)
                    });
                });
            });

            document.getElementById('modal-delete-btn').addEventListener('click', function() {
                if (currentIndex === -1 || !confirm('Delete permanently?')) return;
                const item = items[currentIndex];
                fetch(`/admin/media/${item.id}`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                    body: JSON.stringify({_token: '{{ csrf_token() }}', _method: 'DELETE'})
                }).then(() => location.reload());
            });
        });
    </script>

    <div class="mt-4">
        {{ $media->links('cms-dashboard::components.admin.pagination') }}
    </div>

</x-cms-dashboard::layouts.admin>
