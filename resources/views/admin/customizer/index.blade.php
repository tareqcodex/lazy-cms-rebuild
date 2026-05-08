<x-cms-dashboard::layouts.admin>
<x-slot name="title">Customizer &lsaquo; Lazy CMS</x-slot>

{{-- Toast container --}}
<div id="customizer-toast" class="fixed top-6 right-6 z-[99999] flex flex-col gap-2 pointer-events-none" style="min-width:280px;"></div>

<div x-data="customizerApp('{{ $section }}')" x-init="init()" class="flex flex-col" style="height:calc(100vh - 32px - 40px);">

    {{-- ===== TOP BAR ===== --}}
    <div class="flex-shrink-0 flex items-center gap-3 mb-3">
        <h1 class="text-[20px] font-semibold text-[#1d2327] m-0 mr-auto">Customizer</h1>
        <div class="relative">
            <input type="text" x-model="search" @input="filterFields()"
                   placeholder="Search for option(s)"
                   class="wp-input h-8 w-[210px] pl-8 text-[12px]">
            <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-[#8c8f94]"
                  style="font-size:15px !important;">search</span>
        </div>
    </div>

    {{-- ===== MAIN CARD ===== --}}
    <div class="flex flex-1 overflow-hidden rounded border border-[#c3c4c7] shadow-sm bg-white">

        {{-- ===== LEFT SIDEBAR — dark like dashboard ===== --}}
        <div class="flex-shrink-0 bg-[#1d2327] overflow-y-auto overflow-x-hidden" style="width:210px; border-right:1px solid #2c3338;">
            <p class="px-3 pt-3 pb-1 text-[10px] font-semibold text-[#8c8f94] uppercase tracking-wider">Theme Options</p>
            @foreach($sections as $key => $sec)
                <button type="button"
                        @click="switchSection('{{ $key }}')"
                        :class="activeSection === '{{ $key }}'
                            ? 'bg-[#2271b1] text-white'
                            : 'text-[#c3c4c7] hover:bg-[#2c3338] hover:text-[#72aee6]'"
                        class="relative w-full flex items-center gap-2 px-3 py-[9px] text-[13px] transition-colors duration-150 cursor-pointer text-left">
                    <span class="material-symbols-outlined flex-shrink-0 w-[18px] text-center"
                          style="font-size:16px !important; font-variation-settings:'FILL' 1,'wght' 300,'GRAD' 0,'opsz' 20; max-width:18px; max-height:18px;">{{ $sec['icon'] }}</span>
                    <span class="leading-none" style="flex:1; min-width:0; white-space:nowrap;">{{ $sec['title'] }}</span>
                    {{-- Active right-arrow — x-cloak prevents flash before Alpine initializes --}}
                    <span x-cloak x-show="activeSection === '{{ $key }}'"
                          class="absolute -right-px top-1/2 -translate-y-1/2 w-0 h-0 z-10"
                          style="border-top:7px solid transparent; border-bottom:7px solid transparent; border-right:7px solid white;"></span>
                </button>
            @endforeach
        </div>

        {{-- ===== RIGHT CONTENT ===== --}}
        <div class="flex flex-col flex-1 overflow-hidden">

            {{-- Section title bar --}}
            <div class="flex-shrink-0 border-b border-[#c3c4c7] bg-[#f6f7f7] px-5 py-2.5 flex items-center gap-2 min-h-[40px]">
                @foreach($sections as $key => $sec)
                    <span x-show="activeSection === '{{ $key }}'" class="text-[14px] font-semibold text-[#1d2327]">{{ $sec['title'] }}</span>
                @endforeach
                <span x-show="search.length > 0" class="ml-auto text-[11px] text-[#646970]">
                    <span x-text="visibleCount" class="font-semibold"></span> result(s) found
                </span>
            </div>

            {{-- Scrollable content --}}
            <div class="flex-1 overflow-y-auto">
                <form id="customizer-form">
                    @csrf

                    {{-- No results --}}
                    <div x-show="search.length > 0 && visibleCount === 0"
                         class="flex flex-col items-center justify-center py-16 text-[#646970]">
                        <span class="material-symbols-outlined text-[#c3c4c7] mb-2" style="font-size:40px !important;">search_off</span>
                        <p class="text-[13px]">No options found for &ldquo;<span class="font-semibold text-[#1d2327]" x-text="search"></span>&rdquo;</p>
                    </div>

                    @foreach($sections as $sectionKey => $sec)

                        @if($sectionKey === 'import_export')
                            <div x-show="activeSection === '{{ $sectionKey }}' && search.length === 0" class="p-5 space-y-4 max-w-2xl">

                                <div class="border border-[#c3c4c7] rounded">
                                    <div class="px-4 py-2.5 bg-[#f6f7f7] border-b border-[#c3c4c7]">
                                        <h3 class="text-[13px] font-bold text-[#1d2327] m-0">Export Settings</h3>
                                    </div>
                                    <div class="px-4 py-4">
                                        <p class="text-[12px] text-[#646970] mb-3">Download all current theme settings as a JSON backup file.</p>
                                        <a href="{{ route('admin.customizer.export') }}"
                                           class="wp-btn-primary h-8 px-4 text-[12px] inline-flex items-center gap-2">
                                            <span class="material-symbols-outlined" style="font-size:15px !important;">download</span>
                                            Download theme-options.json
                                        </a>
                                    </div>
                                </div>

                                <div class="border border-[#c3c4c7] rounded">
                                    <div class="px-4 py-2.5 bg-[#f6f7f7] border-b border-[#c3c4c7]">
                                        <h3 class="text-[13px] font-bold text-[#1d2327] m-0">Import Settings</h3>
                                    </div>
                                    <div class="px-4 py-4">
                                        <p class="text-[12px] text-[#646970] mb-3">Upload a previously exported JSON file to restore settings.</p>
                                        <form method="POST" action="{{ route('admin.customizer.import') }}" enctype="multipart/form-data" class="flex items-center gap-3">
                                            @csrf
                                            <input type="file" name="import_file" accept=".json" class="text-[12px]" required>
                                            <button type="submit" class="wp-btn-secondary h-8 px-4 text-[12px] flex items-center gap-1.5"
                                                    onclick="return confirm('This will overwrite current settings. Continue?')">
                                                <span class="material-symbols-outlined" style="font-size:15px !important;">upload</span>
                                                Import
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>

                        @else
                            <div x-show="activeSection === '{{ $sectionKey }}' && search.length === 0">
                                <table class="w-full border-collapse">
                                    <tbody>
                                        @foreach($sec['fields'] as $key => $field)
                                            @php
                                                $val   = $settings[$key] ?? ($field['default'] ?? '');
                                                $label = $field['label'] ?? $key;
                                                $desc  = $field['desc']  ?? '';
                                                $type  = $field['type']  ?? 'text';
                                            @endphp
                                            <tr class="field-row border-b border-[#f0f0f1] hover:bg-[#f6f7f7]/60 transition-colors"
                                                data-label="{{ strtolower($label . ' ' . $desc . ' ' . $sectionKey . ' ' . $sections[$sectionKey]['title']) }}">
                                                <th scope="row" class="w-[260px] text-left align-top px-5 py-3.5">
                                                    <label for="field_{{ $key }}" class="text-[13px] font-semibold text-[#2271b1] block mb-0.5 cursor-pointer">{{ $label }}</label>
                                                    @if($desc)
                                                        <p class="text-[11px] text-[#646970] leading-relaxed m-0">{!! $desc !!}</p>
                                                    @endif
                                                </th>
                                                <td class="px-5 py-3.5 align-middle">

                                                    @if($type === 'text' || $type === 'url')
                                                        <input type="text" name="{{ $key }}" id="field_{{ $key }}"
                                                               value="{{ $val }}"
                                                               placeholder="{{ $field['placeholder'] ?? '' }}"
                                                               class="wp-input w-[300px] text-[13px]">

                                                    @elseif($type === 'typography')
                                                        @php
                                                            $tVal = is_array($val) ? $val : (json_decode($val, true) ?: ($field['default'] ?? []));
                                                        @endphp
                                                        <div x-data="typographyComponent('{{ $key }}', {{ json_encode($tVal) }})" x-init="init()" class="typography-container space-y-4">
                                                            {{-- Hidden input for the actual JSON value --}}
                                                            <input type="hidden" name="{{ $key }}" :value="JSON.stringify(fontData)">

                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                                                {{-- Font Family with Search --}}
                                                                <div class="space-y-1.5 relative">
                                                                    <label class="text-[11px] font-semibold text-[#646970] uppercase">Font Family</label>
                                                                    <div class="relative" @click.away="open = false">
                                                                        <button type="button" @click="open = !open" 
                                                                                class="wp-input w-full h-9 flex items-center justify-between px-3 text-[13px] bg-white border border-[#c3c4c7] rounded hover:border-[#2271b1] focus:ring-1 focus:ring-[#2271b1]">
                                                                            <span x-text="fontData.family || 'Select Font'"></span>
                                                                            <span class="material-symbols-outlined text-[#8c8f94]" style="font-size:18px !important;">expand_more</span>
                                                                        </button>
                                                                        
                                                                        <div x-show="open" x-cloak
                                                                             class="absolute z-[100] mt-1 w-full bg-white border border-[#c3c4c7] rounded shadow-xl overflow-hidden">
                                                                            <div class="p-2 border-b border-[#f0f0f1] bg-[#f6f7f7]">
                                                                                <input type="text" x-model="fontSearch" placeholder="Search fonts..." 
                                                                                       class="w-full h-8 px-2 text-[12px] border border-[#d1d5db] rounded focus:outline-none focus:border-[#2271b1]">
                                                                            </div>
                                                                            <div class="max-h-[250px] overflow-y-auto custom-scrollbar">
                                                                                <template x-for="(fonts, category) in filteredFonts" :key="category">
                                                                                    <div>
                                                                                        <div class="px-3 py-1.5 bg-[#f0f0f1] text-[10px] font-bold text-[#646970] uppercase" x-text="category"></div>
                                                                                        <template x-for="font in fonts" :key="font.family">
                                                                                            <div @click="selectFont(font)" 
                                                                                                 class="px-3 py-2 text-[13px] hover:bg-[#2271b1] hover:text-white cursor-pointer transition-colors"
                                                                                                 :class="fontData.family === font.family ? 'bg-[#f0f7ff] text-[#2271b1] font-semibold' : 'text-[#1d2327]'">
                                                                                                <span x-text="font.family"></span>
                                                                                            </div>
                                                                                        </template>
                                                                                    </div>
                                                                                </template>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Variant / Weight --}}
                                                                <div class="space-y-1.5">
                                                                    <label class="text-[11px] font-semibold text-[#646970] uppercase">Variant</label>
                                                                    <select x-model="fontData.variant" class="wp-input w-full h-9 text-[13px]">
                                                                        <template x-for="variant in currentVariants" :key="variant">
                                                                            <option :value="variant" x-text="formatVariant(variant)" :selected="fontData.variant == variant"></option>
                                                                        </template>
                                                                    </select>
                                                                </div>

                                                                {{-- Font Size --}}
                                                                <div class="space-y-1.5">
                                                                    <label class="text-[11px] font-semibold text-[#646970] uppercase">Font Size</label>
                                                                    <input type="text" x-model="fontData.size" placeholder="16px" class="wp-input w-full h-9 text-[13px]">
                                                                </div>

                                                                {{-- Line Height --}}
                                                                <div class="space-y-1.5">
                                                                    <label class="text-[11px] font-semibold text-[#646970] uppercase">Line Height</label>
                                                                    <input type="text" x-model="fontData.line_height" placeholder="1.6" class="wp-input w-full h-9 text-[13px]">
                                                                </div>

                                                                {{-- Letter Spacing --}}
                                                                <div class="space-y-1.5">
                                                                    <label class="text-[11px] font-semibold text-[#646970] uppercase">Letter Spacing</label>
                                                                    <input type="text" x-model="fontData.letter_spacing" placeholder="0px" class="wp-input w-full h-9 text-[13px]">
                                                                </div>

                                                                {{-- Text Transform --}}
                                                                <div class="space-y-1.5">
                                                                    <label class="text-[11px] font-semibold text-[#646970] uppercase">Transform</label>
                                                                    <div class="flex border border-[#c3c4c7] rounded overflow-hidden">
                                                                        <template x-for="option in ['none', 'capitalize', 'uppercase', 'lowercase']" :key="option">
                                                                            <button type="button" @click="fontData.text_transform = option"
                                                                                    class="flex-1 h-9 flex items-center justify-center transition-colors border-r border-[#c3c4c7] last:border-r-0"
                                                                                    :class="fontData.text_transform === option ? 'bg-[#2271b1] text-white' : 'bg-[#f6f7f7] text-[#50575e] hover:bg-white'">
                                                                                <span class="material-symbols-outlined" style="font-size:16px !important;" x-text="getTransformIcon(option)" :title="option"></span>
                                                                            </button>
                                                                        </template>
                                                                    </div>
                                                                </div>

                                                                {{-- Font Style / Decoration --}}
                                                                <div class="space-y-1.5">
                                                                    <label class="text-[11px] font-semibold text-[#646970] uppercase">Style & Decor</label>
                                                                    <div class="flex border border-[#c3c4c7] rounded overflow-hidden">
                                                                        <button type="button" @click="toggleStyle('italic')"
                                                                                class="flex-1 h-9 flex items-center justify-center border-r border-[#c3c4c7]"
                                                                                :class="fontData.font_style === 'italic' ? 'bg-[#2271b1] text-white' : 'bg-[#f6f7f7] text-[#50575e] hover:bg-white'">
                                                                            <span class="material-symbols-outlined" style="font-size:18px !important;">format_italic</span>
                                                                        </button>
                                                                        <template x-for="option in ['none', 'underline', 'line-through']" :key="option">
                                                                            <button type="button" @click="toggleStyle(option)"
                                                                                    class="flex-1 h-9 flex items-center justify-center transition-colors border-r border-[#c3c4c7] last:border-r-0"
                                                                                    :class="(option === 'none' && fontData.font_style === 'normal' && fontData.text_decoration === 'none') || (option !== 'none' && fontData.text_decoration === option) ? 'bg-[#2271b1] text-white' : 'bg-[#f6f7f7] text-[#50575e] hover:bg-white'">
                                                                                <span class="material-symbols-outlined" style="font-size:18px !important;" x-text="getDecorIcon(option)" :title="option"></span>
                                                                            </button>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Live Preview --}}
                                                            <div class="mt-4 p-4 border border-[#e5e7eb] rounded bg-white overflow-hidden">
                                                                <div class="flex items-center justify-between mb-3 border-b border-[#f0f0f1] pb-2">
                                                                    <span class="text-[10px] font-bold text-[#8c8f94] uppercase tracking-wider">Live Preview</span>
                                                                    <span class="text-[10px] text-[#2271b1] font-medium" x-text="fontData.family + ' ' + formatVariant(fontData.variant)"></span>
                                                                </div>
                                                                <div class="preview-text" :style="getPreviewStyle()">
                                                                    1234567890ABCDEFGHIJKLMN OPQRSTUVWXY Zabcdefghijklm nopqrstuvwxyz
                                                                </div>
                                                            </div>
                                                        </div>

                                                    @elseif($type === 'textarea')

                                                            {{-- Live Preview --}}
                                                            <div class="mt-4 p-4 border border-[#e5e7eb] rounded bg-white overflow-hidden">
                                                                <div class="flex items-center justify-between mb-3 border-b border-[#f0f0f1] pb-2">
                                                                    <span class="text-[10px] font-bold text-[#8c8f94] uppercase tracking-wider">Live Preview</span>
                                                                    <span class="text-[10px] text-[#2271b1] font-medium" x-text="fontData.family + ' ' + formatVariant(fontData.variant)"></span>
                                                                </div>
                                                                <div class="preview-text" :style="getPreviewStyle()">
                                                                    1234567890ABCDEFGHIJKLMN OPQRSTUVWXY Zabcdefghijklm nopqrstuvwxyz
                                                                </div>
                                                            </div>
                                                        </div>

                                                    @elseif($type === 'textarea')
                                                        <textarea name="{{ $key }}" id="field_{{ $key }}"
                                                                  rows="4"
                                                                  placeholder="{{ $field['placeholder'] ?? '' }}"
                                                                  class="wp-input w-[340px] text-[13px] resize-y pt-2 leading-relaxed">{{ $val }}</textarea>

                                                    @elseif($type === 'css')
                                                        <textarea name="{{ $key }}" id="field_{{ $key }}"
                                                                  rows="18"
                                                                  placeholder="{{ $field['placeholder'] ?? '' }}"
                                                                  class="w-full font-mono text-[12px] bg-[#1d1f27] text-[#c5c8c6] border border-[#3c434a] rounded p-4 resize-y leading-relaxed focus:outline-none focus:border-[#2271b1]"
                                                                  style="min-height:280px; tab-size:4;">{{ $val }}</textarea>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    @endforeach

                    {{-- Search results --}}
                    <div x-show="search.length > 0 && visibleCount > 0">
                        <table class="w-full border-collapse">
                            <tbody>
                                @foreach($sections as $sectionKey => $sec)
                                    @if($sectionKey !== 'import_export')
                                        @foreach($sec['fields'] as $key => $field)
                                            @php
                                                $label = $field['label'] ?? $key;
                                                $desc  = $field['desc']  ?? '';
                                            @endphp
                                            <tr class="search-field-row border-b border-[#f0f0f1] hover:bg-[#f6f7f7]/60 transition-colors hidden"
                                                data-search="{{ strtolower($label . ' ' . $desc . ' ' . $sec['title']) }}">
                                                <th scope="row" class="w-[260px] text-left align-top px-5 py-3.5">
                                                    <span class="text-[10px] font-semibold text-[#8c8f94] uppercase tracking-wide block mb-0.5">{{ $sec['title'] }}</span>
                                                    <span class="text-[13px] font-semibold text-[#2271b1] block mb-1">{{ $label }}</span>
                                                    @if($desc)
                                                        <p class="text-[11px] text-[#646970] leading-relaxed m-0">{!! $desc !!}</p>
                                                    @endif
                                                </th>
                                                <td class="px-5 py-3.5 align-middle text-[12px] text-[#646970] italic">
                                                    Go to
                                                    <button type="button"
                                                            @click="switchSection('{{ $sectionKey }}'); search = ''; filterFields();"
                                                            class="text-[#2271b1] underline not-italic cursor-pointer font-medium">{{ $sec['title'] }}</button>
                                                    to edit this field.
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>

            {{-- ===== STICKY FOOTER BAR ===== --}}
            <div class="flex-shrink-0 border-t border-[#c3c4c7] bg-[#f6f7f7] px-5 py-2.5 flex items-center gap-2">
                <span class="text-[11px] text-[#8c8f94] mr-auto">All sections saved together.</span>
                <button type="button" @click="ajaxReset('all')" class="wp-btn-secondary h-8 px-3 text-[12px]">Reset All</button>
                <button type="button" @click="ajaxReset('section')" class="wp-btn-secondary h-8 px-3 text-[12px]">Reset Section</button>
                <button type="button" @click="ajaxSave()"
                        class="wp-btn-primary h-8 px-5 text-[12px] font-semibold flex items-center gap-1.5">
                    <span class="material-symbols-outlined" id="save-icon" style="font-size:15px !important;">save</span>
                    <span id="save-label">Save Changes</span>
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@melloware/coloris/dist/coloris.min.css">
<script src="https://cdn.jsdelivr.net/npm/@melloware/coloris/dist/coloris.min.js"></script>
<script>
// Coloris — called directly (scripts are at end of body, DOM is ready)
Coloris({
    theme: 'default',
    themeMode: 'light',
    alpha: false,
    format: 'hex',
    closeButton: true,
    closeLabel: 'Done',
    clearButton: false,
    onChange(color, input) {
        const key = input.dataset.ckey;
        if (!key) return;
        const sw = document.getElementById('swatch_' + key);
        if (sw) sw.style.background = color;
    }
});

// Segmented button group: update visual state on radio change
function customizerBtnGroup(radio) {
    const group = radio.closest('.cstz-btn-group');
    group.querySelectorAll('.cstz-btn-opt').forEach(span => {
        span.style.background = '#f6f7f7';
        span.style.color = '#50575e';
    });
    radio.nextElementSibling.style.background = '#2271b1';
    radio.nextElementSibling.style.color = '#fff';
}

function customizerApp(initialSection) {
    return {
        activeSection: initialSection || 'layout',
        search: '',
        visibleCount: 0,

        init() {
            const hash   = window.location.hash.replace('#', '');
            const stored = localStorage.getItem('customizer-section');
            if (hash) {
                this.activeSection = hash;
            } else if (stored) {
                this.activeSection = stored;
            }
        },

        switchSection(key) {
            this.activeSection = key;
            localStorage.setItem('customizer-section', key);
            history.replaceState(null, '', window.location.pathname + '#' + key);
            this.search = '';
            this.filterFields();
        },

        filterFields() {
            const q = this.search.toLowerCase().trim();
            if (!q) {
                document.querySelectorAll('.search-field-row').forEach(r => r.classList.add('hidden'));
                this.visibleCount = 0;
                return;
            }
            let count = 0;
            document.querySelectorAll('.search-field-row').forEach(row => {
                const show = (row.dataset.search || '').includes(q);
                row.classList.toggle('hidden', !show);
                if (show) count++;
            });
            this.visibleCount = count;
        },

        getCsrf() {
            return document.querySelector('#customizer-form input[name="_token"]')?.value || '';
        },

        async ajaxSave() {
            const form     = document.getElementById('customizer-form');
            const formData = new FormData(form);
            formData.set('_section', this.activeSection);

            const icon  = document.getElementById('save-icon');
            const label = document.getElementById('save-label');
            icon.textContent  = 'hourglass_top';
            label.textContent = 'Saving…';

            try {
                const res  = await fetch('{{ route("admin.customizer.save") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await res.json();
                this.showToast(data.message || 'Saved.', data.success ? 'success' : 'error');
            } catch {
                this.showToast('Error saving settings. Please try again.', 'error');
            } finally {
                icon.textContent  = 'save';
                label.textContent = 'Save Changes';
            }
        },

        async ajaxReset(type) {
            const msg = type === 'all'
                ? 'Reset ALL settings to defaults? This cannot be undone.'
                : 'Reset this section to default values?';
            if (!confirm(msg)) return;

            const fd = new FormData();
            fd.append('_token', this.getCsrf());
            if (type === 'all') {
                fd.append('all', '1');
            } else {
                fd.append('section', this.activeSection);
            }

            try {
                const res  = await fetch('{{ route("admin.customizer.reset") }}', {
                    method: 'POST',
                    body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await res.json();
                this.showToast(data.message || 'Reset.', data.success ? 'success' : 'error');
                if (data.success && data.reload) {
                    setTimeout(() => window.location.reload(), 900);
                }
            } catch {
                this.showToast('Error resetting settings. Please try again.', 'error');
            }
        },

        showToast(message, type) {
            const container = document.getElementById('customizer-toast');
            const el = document.createElement('div');
            const ok = type === 'success';
            el.style.cssText = 'pointer-events:auto; opacity:0; transform:translateY(-8px); transition:opacity .2s,transform .2s;';
            el.className = `flex items-center gap-2 px-4 py-3 rounded-lg shadow-xl text-[13px] font-medium text-white ${ok ? 'bg-[#00a32a]' : 'bg-[#b32d2e]'}`;
            el.innerHTML = `<span class="material-symbols-outlined" style="font-size:17px !important;">${ok ? 'check_circle' : 'error'}</span><span>${message}</span>`;
            container.appendChild(el);
            requestAnimationFrame(() => {
                el.style.opacity   = '1';
                el.style.transform = 'translateY(0)';
            });
            setTimeout(() => {
                el.style.opacity   = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(() => el.remove(), 220);
            }, 3200);
        },
    };
}

// Typography Component Logic
const GOOGLE_FONTS = {
    'Sans-serif': [
        { family: 'Inter', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Roboto', variants: ['100', '300', '400', '500', '700', '900'] },
        { family: 'Open Sans', variants: ['300', '400', '500', '600', '700', '800'] },
        { family: 'Lato', variants: ['100', '300', '400', '700', '900'] },
        { family: 'Poppins', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Nunito', variants: ['200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Montserrat', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Source Sans 3', variants: ['200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Raleway', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Ubuntu', variants: ['300', '400', '500', '700'] },
        { family: 'Oswald', variants: ['200', '300', '400', '500', '600', '700'] },
        { family: 'Quicksand', variants: ['300', '400', '500', '600', '700'] },
        { family: 'PT Sans', variants: ['400', '700'] },
        { family: 'Mukta', variants: ['200', '300', '400', '500', '600', '700', '800'] },
        { family: 'Work Sans', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Nanum Gothic', variants: ['400', '700', '800'] },
        { family: 'Noto Sans', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Rubik', variants: ['300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Heebo', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Karla', variants: ['200', '300', '400', '500', '600', '700', '800'] },
        { family: 'Arimo', variants: ['400', '500', '600', '700'] },
        { family: 'Cabin', variants: ['400', '500', '600', '700'] },
        { family: 'Libre Franklin', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Barlow', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'DM Sans', variants: ['400', '500', '700'] },
        { family: 'Questrial', variants: ['400'] },
        { family: 'Cairo', variants: ['200', '300', '400', '500', '600', '700', '800', '900'] }
    ],
    'Serif': [
        { family: 'Playfair Display', variants: ['400', '500', '600', '700', '800', '900'] },
        { family: 'Merriweather', variants: ['300', '400', '700', '900'] },
        { family: 'Lora', variants: ['400', '500', '600', '700'] },
        { family: 'PT Serif', variants: ['400', '700'] },
        { family: 'Libre Baskerville', variants: ['400', '700'] },
        { family: 'Crimson Text', variants: ['400', '600', '700'] },
        { family: 'Noto Serif', variants: ['400', '700'] },
        { family: 'Arvo', variants: ['400', '700'] },
        { family: 'EB Garamond', variants: ['400', '500', '600', '700', '800'] },
        { family: 'Old Standard TT', variants: ['400', '700'] },
        { family: 'Bitter', variants: ['100', '200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Cardo', variants: ['400', '700'] },
        { family: 'Cormorant Garamond', variants: ['300', '400', '500', '600', '700'] },
        { family: 'Domine', variants: ['400', '500', '600', '700'] },
        { family: 'Spectral', variants: ['200', '300', '400', '500', '600', '700', '800'] },
        { family: 'Zilla Slab', variants: ['300', '400', '500', '600', '700'] },
        { family: 'Manrope', variants: ['200', '300', '400', '500', '600', '700', '800'] }
    ],
    'Display': [
        { family: 'Abril Fatface', variants: ['400'] },
        { family: 'Lobster', variants: ['400'] },
        { family: 'Pacifico', variants: ['400'] },
        { family: 'Righteous', variants: ['400'] },
        { family: 'Bebas Neue', variants: ['400'] },
        { family: 'Cinzel', variants: ['400', '500', '600', '700', '800', '900'] },
        { family: 'Anton', variants: ['400'] },
        { family: 'Comfortaa', variants: ['300', '400', '500', '600', '700'] },
        { family: 'Dancing Script', variants: ['400', '500', '600', '700'] },
        { family: 'Satisfy', variants: ['400'] },
        { family: 'Patua One', variants: ['400'] },
        { family: 'Luckiest Guy', variants: ['400'] },
        { family: 'Permanent Marker', variants: ['400'] },
        { family: 'Fredoka One', variants: ['400'] },
        { family: 'Alpha Slab One', variants: ['400'] },
        { family: 'Bangers', variants: ['400'] },
        { family: 'Cookie', variants: ['400'] },
        { family: 'Great Vibes', variants: ['400'] },
        { family: 'Kaushan Script', variants: ['400'] },
        { family: 'Shadows Into Light', variants: ['400'] },
        { family: 'Courgette', variants: ['400'] },
        { family: 'Gloria Hallelujah', variants: ['400'] },
        { family: 'Homemade Apple', variants: ['400'] },
        { family: 'Yellowtail', variants: ['400'] }
    ],
    'Monospace': [
        { family: 'Fira Code', variants: ['300', '400', '500', '600', '700'] },
        { family: 'Source Code Pro', variants: ['200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Roboto Mono', variants: ['100', '200', '300', '400', '500', '600', '700'] },
        { family: 'JetBrains Mono', variants: ['100', '200', '300', '400', '500', '600', '700', '800'] },
        { family: 'Inconsolata', variants: ['200', '300', '400', '500', '600', '700', '800', '900'] },
        { family: 'Space Mono', variants: ['400', '700'] },
        { family: 'Ubuntu Mono', variants: ['400', '700'] },
        { family: 'Courier Prime', variants: ['400', '700'] },
        { family: 'IBM Plex Mono', variants: ['100', '200', '300', '400', '500', '600', '700'] },
        { family: 'Share Tech Mono', variants: ['400'] },
        { family: 'Anonymous Pro', variants: ['400', '700'] }
    ]
};

function typographyComponent(key, initialData) {
    return {
        open: false,
        fontSearch: '',
        fontData: initialData,
        
        init() {
            // Load the font on init for preview
            this.loadGoogleFont(this.fontData.family);
        },

        toggleStyle(op) {
            if (op === 'italic') {
                this.fontData.font_style = (this.fontData.font_style === 'italic' ? 'normal' : 'italic');
                if (this.fontData.font_style === 'italic') this.fontData.text_decoration = 'none';
            } else if (op === 'none') {
                this.fontData.font_style = 'normal';
                this.fontData.text_decoration = 'none';
            } else {
                this.fontData.text_decoration = (this.fontData.text_decoration === op ? 'none' : op);
                if (this.fontData.text_decoration !== 'none') this.fontData.font_style = 'normal';
            }
        },

        get filteredFonts() {
            if (!this.fontSearch) return GOOGLE_FONTS;
            const s = this.fontSearch.toLowerCase();
            const filtered = {};
            for (const [cat, fonts] of Object.entries(GOOGLE_FONTS)) {
                const matched = fonts.filter(f => f.family.toLowerCase().includes(s));
                if (matched.length) filtered[cat] = matched;
            }
            return filtered;
        },

        get currentVariants() {
            for (const cat in GOOGLE_FONTS) {
                const found = GOOGLE_FONTS[cat].find(f => f.family === this.fontData.family);
                if (found) return found.variants;
            }
            return ['400'];
        },

        selectFont(font) {
            this.fontData.family = font.family;
            // Reset variant if previous one doesn't exist in new font
            if (!font.variants.includes(this.fontData.variant)) {
                this.fontData.variant = font.variants.includes('400') ? '400' : font.variants[0];
            }
            this.loadGoogleFont(font.family);
            this.open = false;
        },

        formatVariant(v) {
            const weights = {
                '100': 'Thin (100)',
                '200': 'Extra Light (200)',
                '300': 'Light (300)',
                '400': 'Regular (400)',
                '500': 'Medium (500)',
                '600': 'Semi Bold (600)',
                '700': 'Bold (700)',
                '800': 'Extra Bold (800)',
                '900': 'Black (900)'
            };
            return weights[v] || v;
        },

        loadGoogleFont(family) {
            if (!family || family === 'System Default') return;
            const linkId = 'gfont-' + family.replace(/\s+/g, '-').toLowerCase();
            if (document.getElementById(linkId)) return;
            const link = document.createElement('link');
            link.id = linkId;
            link.rel = 'stylesheet';
            link.href = `https://fonts.googleapis.com/css2?family=${family.replace(/\s+/g, '+')}:wght@100;200;300;400;500;600;700;800;900&display=swap`;
            document.head.appendChild(link);
        },

        getTransformIcon(op) {
            const icons = { 'none': 'block', 'capitalize': 'match_case', 'uppercase': 'uppercase', 'lowercase': 'lowercase' };
            return icons[op] || 'block';
        },

        getDecorIcon(op) {
            const icons = { 'none': 'format_clear', 'underline': 'format_underlined', 'line-through': 'format_strikethrough' };
            return icons[op] || 'format_clear';
        },

        getPreviewStyle() {
            return {
                fontFamily: `'${this.fontData.family}', sans-serif`,
                fontWeight: this.fontData.variant,
                fontSize: this.fontData.size,
                lineHeight: this.fontData.line_height,
                letterSpacing: this.fontData.letter_spacing,
                textTransform: this.fontData.text_transform,
                textDecoration: this.fontData.text_decoration,
                fontStyle: this.fontData.font_style,
                transition: 'all 0.2s'
            };
        }
    };
}
</script>

<style>
/* Customizer Panel Width - Reduced by 30% */
#customizer-app {
    max-width: 980px !important; /* Original was ~1400px, 70% of 1400 is 980 */
    margin: 20px auto;
}
/* Customizer scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #c3c4c7; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #8c8f94; }

/* Preview Text */
.preview-text {
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #1d2327;
    background: #fdfdfd;
    border-radius: 4px;
    padding: 20px;
}

/* Coloris picker z-index */
#clr-picker { z-index: 99999 !important; }
/* Coloris swatch button */
.clr-field button { width: 30px; height: 30px; border-radius: 3px; border: 1px solid #c3c4c7; }
/* Segmented btn hover (non-active) */
.cstz-btn-opt:hover { filter: brightness(0.97); }
</style>
@endpush
</x-cms-dashboard::layouts.admin>
