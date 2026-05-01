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

        <div class="max-w-[800px]">
            <form action="{{ route('admin.settings.theme-options.update') }}" method="POST">
                @csrf

                @include('cms-dashboard::components.admin.dynamic-fields')

                {{-- Bulk Actions (non-saveable, separate button) --}}
                <div class="mt-6 pt-6 border-t border-[#c3c4c7]">
                    <table class="w-full border-separate border-spacing-y-6">
                        <tr>
                            <th scope="row" class="w-[200px] text-left align-top pt-2">
                                <label class="text-[14px] font-semibold text-[#1d2327]">Bulk Actions</label>
                            </th>
                            <td>
                                <button type="button" id="bulk-optimize-btn" class="wp-btn-secondary px-4 h-8">
                                    Optimize Existing Images Now
                                </button>
                                <p class="text-[12px] text-[#b32d2e] mt-2 font-medium">Caution: This will replace all existing original images with optimized versions. This process cannot be undone.</p>
                                <div id="optimization-status" class="hidden mt-2 text-[13px] font-medium">
                                    <span class="text-[#2271b1]">Optimizing images, please wait...</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="pt-6 border-t border-gray-100 mt-4">
                    <button type="submit" class="wp-btn-primary px-4 h-8 font-semibold">Save Changes</button>
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
