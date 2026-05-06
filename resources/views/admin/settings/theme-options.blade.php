<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Theme Options - Lazy CMS</x-slot>

    <div class="px-2">
        <h1 class="text-[23px] font-normal text-[#1d2327] mb-4">Settings</h1>

        @include('cms-dashboard::admin.settings.nav')

        @if (session('success'))
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-6 text-[13px] text-[#1d2327]">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-[1000px]">
            <form action="{{ route('admin.settings.theme-options.update') }}" method="POST">
                @csrf

                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                        <h2 class="text-[15px] font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa fa-sliders-h text-[#2271b1]"></i>
                            Configure Theme Options
                        </h2>
                        <button type="submit" class="bg-[#2271b1] hover:bg-[#1a5b8e] text-white px-5 py-2 rounded-md text-[13px] font-bold shadow-sm transition-all flex items-center gap-2">
                            <i class="fa fa-save text-[11px]"></i>
                            Save Changes
                        </button>
                    </div>

                    <div class="p-8">
                        @include('cms-dashboard::components.admin.dynamic-fields')

                        {{-- Bulk Actions (non-saveable, separate button) --}}
                        <div class="mt-12 pt-10 border-t border-slate-200">
                            <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-4">
                                <div>
                                    <h3 class="text-[14px] font-bold text-slate-700">Maintenance & Optimization</h3>
                                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Perform system-wide optimizations.</p>
                                </div>
                                <div class="bg-red-50/50 border border-red-100 rounded-lg p-5">
                                    <button type="button" id="bulk-optimize-btn" class="bg-white border border-red-200 text-red-600 hover:bg-red-600 hover:text-white px-6 py-2 rounded-md text-[12px] font-bold transition-all shadow-sm flex items-center gap-2">
                                        <i class="fa fa-bolt"></i>
                                        Optimize Existing Images Now
                                    </button>
                                    <div class="mt-3 flex items-start gap-2">
                                        <i class="fa fa-exclamation-triangle text-red-400 text-[10px] mt-1"></i>
                                        <p class="text-[11.5px] text-red-600 font-medium leading-relaxed">
                                            <span class="font-bold">Caution:</span> This will replace all existing original images with optimized versions. This process cannot be undone.
                                        </p>
                                    </div>
                                    <div id="optimization-status" class="hidden mt-4 p-3 bg-white border border-red-100 rounded flex items-center gap-3">
                                        <div class="w-4 h-4 border-2 border-red-600 border-t-transparent rounded-full animate-spin"></div>
                                        <span class="text-[12px] text-red-700 font-bold">Optimizing images, please wait...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end items-center gap-4">
                        <span class="text-[12px] text-slate-400 italic">Last saved: {{ now()->diffForHumans() }}</span>
                        <button type="submit" class="bg-[#2271b1] hover:bg-[#1a5b8e] text-white px-8 py-2 rounded-md text-[13px] font-bold shadow-sm transition-all">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Media modal for logo/favicon fields
        document.querySelectorAll('.open-media-for-setting').forEach(btn => {
            btn.addEventListener('click', function () {
                const target = this.getAttribute('data-target');
                window.openMediaModal(function (media) {
                    const input = document.getElementById('input-' + target);
                    if (input) input.value = media.path;
                    const preview = document.getElementById('media-preview-' + target);
                    if (preview) {
                        preview.innerHTML = `<img src="/storage/${media.path}" class="max-w-full max-h-full object-contain">`;
                        preview.classList.remove('hidden');
                    }
                });
            });
        });

        // Bulk Optimize
        const optimizeBtn = document.getElementById('bulk-optimize-btn');
        const statusDiv   = document.getElementById('optimization-status');
        if (optimizeBtn) {
            optimizeBtn.addEventListener('click', function () {
                if (!confirm('Are you sure? This will replace original files and may take some time.')) return;
                optimizeBtn.disabled = true;
                optimizeBtn.innerText = 'Processing...';
                statusDiv.classList.remove('hidden');
                fetch("{{ route('admin.media.bulk-optimize') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.success ? data.message : 'Error: ' + data.message);
                    if (data.success) location.reload();
                })
                .catch(() => alert('An unexpected error occurred.'))
                .finally(() => {
                    optimizeBtn.disabled = false;
                    optimizeBtn.innerText = 'Optimize Existing Images Now';
                    statusDiv.classList.add('hidden');
                });
            });
        }
    });
    </script>
    @endpush
</x-cms-dashboard::layouts.admin>
