@php
    $seo = is_array($post->seo_meta) ? $post->seo_meta : [];
    $seoTitle = $seo['title'] ?? ($post->title ?? '');
@endphp

<div class="wp-metabox mt-5">
    <div class="wp-metabox-header">
        <span class="font-bold">SEO Settings</span>
    </div>
    <div class="wp-metabox-content p-5 space-y-5">
        {{-- SEO Title --}}
        <div>
            <div class="flex justify-between items-center mb-1">
                <label class="block text-[13px] font-bold text-[#2c3338]">SEO Title</label>
                <span id="seo-title-count" class="text-[11px] text-[#646970]">0 characters</span>
            </div>
            <input type="text" name="seo[title]" id="seo-title-input" value="{{ old('seo.title', $seoTitle) }}" 
                   class="wp-input w-full" placeholder="Enter SEO title...">
            <div class="mt-1.5 h-1 w-full bg-[#f0f0f1] rounded-full overflow-hidden">
                <div id="seo-title-bar" class="h-full w-0 transition-all duration-300"></div>
            </div>
            <p class="text-[11px] text-[#646970] mt-1">Ideal: 50-60 characters.</p>
        </div>

        {{-- SEO Description --}}
        <div>
            <div class="flex justify-between items-center mb-1">
                <label class="block text-[13px] font-bold text-[#2c3338]">Meta Description</label>
                <span id="seo-desc-count" class="text-[11px] text-[#646970]">0 characters</span>
            </div>
            <textarea name="seo[description]" id="seo-desc-input" rows="3" class="wp-input w-full" 
                      placeholder="Enter meta description...">{{ old('seo.description', $seo['description'] ?? '') }}</textarea>
            <div class="mt-1.5 h-1 w-full bg-[#f0f0f1] rounded-full overflow-hidden">
                <div id="seo-desc-bar" class="h-full w-0 transition-all duration-300"></div>
            </div>
            <p class="text-[11px] text-[#646970] mt-1">Ideal: 150-160 characters.</p>
        </div>

        {{-- SEO Keywords --}}
        <div>
            <label class="block text-[13px] font-bold text-[#2c3338] mb-1">Meta Keywords</label>
            <input type="text" name="seo[keywords]" value="{{ old('seo.keywords', $seo['keywords'] ?? '') }}" 
                   class="wp-input w-full" placeholder="keyword1, keyword2, ...">
        </div>

        {{-- Social Image (OG Image) --}}
        <div>
            <label class="block text-[13px] font-bold text-[#2c3338] mb-1">Social Share Image (OG Image)</label>
            <div class="flex items-center gap-3">
                <div id="seo-og-preview" class="w-24 h-24 bg-[#f0f0f1] border border-[#dfdfdf] rounded flex items-center justify-center overflow-hidden cursor-pointer" onclick="openSeoMedia()">
                    @if(!empty($seo['og_image']))
                        <img src="{{ asset('storage/' . $seo['og_image']) }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-8 h-8 text-[#dcdcde]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @endif
                </div>
                <div class="flex-1 space-y-2">
                    <div class="flex gap-2">
                        <input type="text" name="seo[og_image]" id="seo-og-image" value="{{ old('seo.og_image', $seo['og_image'] ?? '') }}" 
                               class="wp-input flex-1 h-8 text-[12px]" placeholder="Image path...">
                        <button type="button" onclick="openSeoMedia()" 
                                class="wp-btn-secondary h-8 py-0 px-3 text-[12px]">Select Image</button>
                    </div>
                    <p class="text-[12px] text-[#646970]">Recommended size: 1200x630px.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openSeoMedia() {
        if (typeof window.openMediaModal === 'function') {
            window.openMediaModal(function(media) {
                const input = document.getElementById('seo-og-image');
                const preview = document.getElementById('seo-og-preview');
                if (input) input.value = media.path;
                if (preview) {
                    preview.innerHTML = `<img src="/storage/${media.path}" class="w-full h-full object-cover">`;
                }
            });
        } else {
            alert('Media manager not found.');
        }
    }

    function updateSeoBar(inputId, barId, countId, minOk, maxOk, maxLimit) {
        const input = document.getElementById(inputId);
        const bar = document.getElementById(barId);
        const count = document.getElementById(countId);
        if (!input || !bar || !count) return;

        const len = input.value.length;
        count.innerText = `${len} characters`;

        let percentage = (len / maxLimit) * 100;
        if (percentage > 100) percentage = 100;
        bar.style.width = `${percentage}%`;

        if (len === 0) {
            bar.style.backgroundColor = '#f0f0f1';
        } else if (len < minOk) {
            bar.style.backgroundColor = '#d63638'; // Red
        } else if (len >= minOk && len <= maxOk) {
            bar.style.backgroundColor = '#00a32a'; // Green
        } else {
            bar.style.backgroundColor = '#dba617'; // Yellow/Orange
        }
    }

    const seoTitleInput = document.getElementById('seo-title-input');
    const seoDescInput = document.getElementById('seo-desc-input');

    if (seoTitleInput) {
        seoTitleInput.addEventListener('input', () => updateSeoBar('seo-title-input', 'seo-title-bar', 'seo-title-count', 50, 60, 80));
        updateSeoBar('seo-title-input', 'seo-title-bar', 'seo-title-count', 50, 60, 80);
    }

    if (seoDescInput) {
        seoDescInput.addEventListener('input', () => updateSeoBar('seo-desc-input', 'seo-desc-bar', 'seo-desc-count', 150, 160, 200));
        updateSeoBar('seo-desc-input', 'seo-desc-bar', 'seo-desc-count', 150, 160, 200);
    }

    // Title Sync Logic
    const mainTitleInput = document.getElementById('title-input');
    let isSeoTitleManuallyEdited = {{ !empty($seo['title']) ? 'true' : 'false' }};

    if (seoTitleInput && mainTitleInput) {
        mainTitleInput.addEventListener('input', function() {
            if (!isSeoTitleManuallyEdited) {
                seoTitleInput.value = this.value;
                updateSeoBar('seo-title-input', 'seo-title-bar', 'seo-title-count', 50, 60, 80);
            }
        });

        seoTitleInput.addEventListener('input', function() {
            isSeoTitleManuallyEdited = true;
        });
    }
</script>
