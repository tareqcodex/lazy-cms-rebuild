<div id="wp-media-modal" class="hidden fixed inset-0 z-[99999] overflow-hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <!-- Modal Container -->
    <div class="absolute inset-4 sm:inset-10 bg-white shadow-2xl flex flex-col overflow-hidden border border-[#c3c4c7]">
        
        <!-- Header -->
        <div class="h-[50px] border-bottom border-[#c3c4c7] flex justify-between items-center px-4 shrink-0 bg-white">
            <h1 class="text-[22px] font-normal text-[#1d2327]">Add media</h1>
            <button type="button" id="close-media-modal" class="text-[#646970] hover:text-black p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Main Body -->
        <div class="flex flex-grow overflow-hidden">
            
            <!-- Sidebar Left (Tabs) -->
            <div class="w-[160px] border-r border-[#c3c4c7] bg-[#f0f0f1] py-4 shrink-0 hidden md:block">
                <ul class="text-[14px]">
                    <li><a href="#" class="media-modal-tab-btn active block px-4 py-2 border-l-4 border-l-[#2271b1] bg-white text-black font-semibold" data-target="media-upload-view">Upload files</a></li>
                    <li><a href="#" class="media-modal-tab-btn block px-4 py-2 border-l-4 border-l-transparent text-[#2271b1] hover:text-[#135e96]" data-target="media-library-view">Media Library</a></li>
                </ul>
            </div>

            <!-- Content Area -->
            <div class="flex-grow flex flex-col overflow-hidden">
                
                <!-- Tab Views -->
                <div class="flex-grow overflow-y-auto p-4 flex flex-col">
                    
                    <!-- Upload Files View -->
                    <div id="media-upload-view" class="media-tab-content flex-grow flex flex-col items-center justify-center border-2 border-dashed border-[#c3c4c7] rounded-sm bg-[#f6f7f7]">
                        <div class="text-center">
                            <h2 class="text-[20px] mb-2 font-normal text-[#1d2327]">Drop files to upload</h2>
                            <p class="text-[14px] text-[#646970] mb-4">or</p>
                            <input type="file" id="media-upload-input" class="hidden" multiple accept="image/*">
                            <button type="button" onclick="document.getElementById('media-upload-input').click()" class="wp-btn-secondary px-6">Select Files</button>
                            <p class="mt-8 text-[12px] text-[#646970]">Maximum upload file size: 10 MB.</p>
                        </div>
                    </div>

                    <!-- Media Library View -->
                    <div id="media-library-view" class="media-tab-content hidden flex-grow relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex space-x-2">
                                <select class="wp-input h-7 text-[12px] py-0">
                                    <option>All media items</option>
                                    <option>Images</option>
                                </select>
                                <select class="wp-input h-7 text-[12px] py-0">
                                    <option>All dates</option>
                                </select>
                            </div>
                            <input type="text" placeholder="Search media items..." class="wp-input h-7 text-[12px] w-48">
                        </div>
                        <div id="media-library-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                            <!-- JS populated -->
                        </div>
                        <div id="media-loading-spinner" class="hidden absolute inset-0 bg-white/50 flex items-center justify-center">
                            <svg class="animate-spin h-8 w-8 text-[#2271b1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>

                </div>

                <!-- Footer (Selected Info) -->
                <div class="h-[60px] border-t border-[#c3c4c7] flex justify-between items-center px-4 shrink-0 bg-white">
                    <div id="selected-summary" class="text-[13px] text-[#646970]">
                        <span id="selected-count">0</span> item selected
                    </div>
                    <button type="button" id="insert-media-btn" class="wp-btn-primary px-6 opacity-50 cursor-not-allowed" disabled>Add Media</button>
                </div>
            </div>

            <!-- Sidebar Right (Attachment Details) -->
            <div class="w-[280px] border-l border-[#c3c4c7] bg-[#f0f0f1] p-4 shrink-0 overflow-y-auto hidden lg:block" id="media-details-sidebar">
                <h3 class="uppercase text-[12px] font-bold text-[#646970] mb-4">Attachment Details</h3>
                <div id="details-empty" class="text-[13px] text-[#646970] italic">Select an item to see details.</div>
                <div id="details-view" class="hidden space-y-4">
                    <div class="flex gap-3">
                        <img id="detail-thumb" src="" class="w-16 h-16 object-cover bg-white border border-[#c3c4c7]">
                        <div class="text-[12px] break-all">
                            <div id="detail-filename" class="font-bold text-black mt-1">image.jpg</div>
                            <div id="detail-date" class="text-[#646970] mb-2">April 17, 2026</div>
                            <div class="pt-2 border-t border-[#c3c4c7] mt-2 space-y-1">
                                <div><strong>Main File size:</strong> <span id="detail-orig-size" class="text-[#646970]"></span></div>
                                <div><strong>Compression Size:</strong> <span id="detail-comp-size" class="text-[#646970]"></span></div>
                                <div><strong>Total Compression:</strong> <span id="detail-pct" class="text-green-600 font-bold"></span></div>
                                <div><strong>Status:</strong> <span id="detail-status" class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase"></span></div>
                            </div>
                            <button type="button" id="delete-media-permanently" class="text-[#b32d2e] hover:underline mt-2 text-[12px]">Delete permanently</button>
                        </div>
                    </div>
                    <hr class="border-[#c3c4c7]">
                    <div class="space-y-3">
                        <div><label class="block text-[12px] text-[#646970] mb-1">Alt Text</label><input type="text" id="meta-alt" class="wp-input w-full text-[13px] h-7"></div>
                        <div><label class="block text-[12px] text-[#646970] mb-1">Title</label><input type="text" id="meta-title" class="wp-input w-full text-[13px] h-7"></div>
                        <div><label class="block text-[12px] text-[#646970] mb-1">Caption</label><textarea id="meta-caption" class="wp-input w-full text-[13px] h-16 py-1"></textarea></div>
                        <div><label class="block text-[12px] text-[#646970] mb-1">Description</label><textarea id="meta-desc" class="wp-input w-full text-[13px] h-16 py-1"></textarea></div>
                        <div><label class="block text-[12px] text-[#646970] mb-1">File URL:</label><input type="text" id="meta-url" readonly class="wp-input w-full text-[12px] h-7 bg-white/50"></div>
                    </div>
                    <div class="text-right pt-2 border-t border-[#c3c4c7]">
                        <button type="button" id="save-media-meta-btn" class="wp-btn-secondary text-[12px] h-7">Save Details</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    (function() {
        const modal = document.getElementById('wp-media-modal');
        const grid = document.getElementById('media-library-grid');
        const detailsView = document.getElementById('details-view');
        const detailsEmpty = document.getElementById('details-empty');
        const insertBtn = document.getElementById('insert-media-btn');
        let selectedMedia = null;
        let currentCallback = null;

        // Global opener
        window.openMediaModal = function(callback) {
            modal.classList.remove('hidden');
            currentCallback = callback;
            loadLibrary();
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            selectedMedia = null;
        };
        document.getElementById('close-media-modal').addEventListener('click', closeModal);

        // Tabs
        document.querySelectorAll('.media-modal-tab-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.media-modal-tab-btn').forEach(b => {
                    b.classList.remove('active', 'bg-white', 'text-black', 'font-semibold', 'border-l-[#2271b1]');
                    b.classList.add('border-l-transparent', 'text-[#2271b1]');
                });
                this.classList.add('active', 'bg-white', 'text-black', 'font-semibold', 'border-l-[#2271b1]');
                this.classList.remove('border-l-transparent', 'text-[#2271b1]');
                
                document.querySelectorAll('.media-tab-content').forEach(c => c.classList.add('hidden'));
                document.getElementById(this.dataset.target).classList.remove('hidden');
                
                if (this.dataset.target === 'media-library-view') loadLibrary();
            });
        });

        // Upload Logic
        const fileInput = document.getElementById('media-upload-input');
        fileInput.addEventListener('change', function() {
            if (!this.files.length) return;
            const formData = new FormData();
            formData.append('file', this.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            // Show library and spinner
            document.querySelector('[data-target="media-library-view"]').click();
            document.getElementById('media-loading-spinner').classList.remove('hidden');

            fetch("{{ route('admin.media.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    loadLibrary();
                } else {
                    alert('Upload failed.');
                }
            })
            .finally(() => {
                document.getElementById('media-loading-spinner').classList.add('hidden');
            });
        });

        // Library Logic
        function loadLibrary() {
            document.getElementById('media-loading-spinner').classList.remove('hidden');
            fetch("{{ route('admin.media.index') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                grid.innerHTML = '';
                data.data.forEach(item => {
                    const div = document.createElement('div');
                    div.className = `relative aspect-square border-2 border-transparent bg-gray-100 cursor-pointer overflow-hidden group item-media-${item.id}`;
                    div.innerHTML = `<img src="/storage/${item.path}" class="w-full h-full object-cover">
                                     <div class="absolute inset-0 border-4 border-[#2271b1] hidden check-overlay"></div>
                                     <div class="absolute top-1 right-1 bg-[#2271b1] text-white rounded-full p-0.5 hidden check-icon"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg></div>`;
                    div.addEventListener('click', () => selectItem(item, div));
                    grid.appendChild(div);
                });
            })
            .finally(() => {
                document.getElementById('media-loading-spinner').classList.add('hidden');
            });
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function selectItem(item, el) {
            document.querySelectorAll('#media-library-grid > div').forEach(d => {
                d.classList.remove('border-[#2271b1]');
                d.querySelector('.check-overlay').classList.add('hidden');
                d.querySelector('.check-icon').classList.add('hidden');
            });
            el.classList.add('border-[#2271b1]');
            el.querySelector('.check-overlay').classList.remove('hidden');
            el.querySelector('.check-icon').classList.remove('hidden');
            
            selectedMedia = item;
            detailsEmpty.classList.add('hidden');
            detailsView.classList.remove('hidden');
            insertBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            insertBtn.disabled = false;
            document.getElementById('selected-count').innerText = '1';

            // Fill details
            document.getElementById('detail-thumb').src = `/storage/${item.path}`;
            document.getElementById('detail-filename').innerText = item.filename;
            document.getElementById('detail-date').innerText = new Date(item.created_at).toLocaleDateString('en-US', {month:'long', day:'numeric', year:'numeric'});
            
            const orig = item.original_size || 0;
            const comp = item.compressed_size || orig;
            
            document.getElementById('detail-orig-size').innerText = formatBytes(orig);
            document.getElementById('detail-comp-size').innerText = formatBytes(comp);
            
            const pctEl = document.getElementById('detail-pct');
            const statusEl = document.getElementById('detail-status');

            if (comp < orig && orig > 0) {
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

            document.getElementById('meta-alt').value = item.alt_text || '';
            document.getElementById('meta-title').value = item.title || '';
            document.getElementById('meta-caption').value = item.caption || '';
            document.getElementById('meta-desc').value = item.description || '';
            document.getElementById('meta-url').value = window.location.origin + '/storage/' + item.path;
        }

        document.getElementById('save-media-meta-btn').addEventListener('click', function() {
            if (!selectedMedia) return;
            const data = {
                alt_text: document.getElementById('meta-alt').value,
                title: document.getElementById('meta-title').value,
                caption: document.getElementById('meta-caption').value,
                description: document.getElementById('meta-desc').value,
                _token: '{{ csrf_token() }}',
                _method: 'PUT'
            };
            fetch(`/admin/media/${selectedMedia.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            }).then(() => alert('Details saved.'));
        });

        document.getElementById('delete-media-permanently').addEventListener('click', function() {
           if (!selectedMedia || !confirm('Are you sure you want to delete this file permanently?')) return;
            fetch(`/admin/media/${selectedMedia.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({_token: '{{ csrf_token() }}', _method: 'DELETE'})
            }).then(() => {
               loadLibrary();
               detailsView.classList.add('hidden');
               detailsEmpty.classList.remove('hidden');
               selectedMedia = null;
               insertBtn.disabled = true;
               insertBtn.classList.add('opacity-50', 'cursor-not-allowed');
           });
        });

        insertBtn.addEventListener('click', function() {
            if (selectedMedia && currentCallback) {
                currentCallback(selectedMedia);
                closeModal();
            }
        });

    })();
</script>
