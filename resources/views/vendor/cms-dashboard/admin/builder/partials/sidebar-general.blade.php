{{-- Container General Settings Partial --}}
<div class="space-y-8 pb-10" x-show="editingContext.type === 'container' && activeSubTab === 'general'">
    <template x-if="layout[editingContext.ci]">
        <div class="space-y-8">
            {{-- Field 1: Interior Content Width --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Interior Content Width</label>
                <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1 rounded-lg">
                    <button @click="layout[editingContext.ci].settings.contentWidth = '100'" :class="layout[editingContext.ci].settings.contentWidth==='100' ? 'active' : ''" class="premium-btn py-2.5 rounded-md text-[10px] font-bold uppercase transition-all">100% Width</button>
                    <button @click="layout[editingContext.ci].settings.contentWidth = 'site'" :class="layout[editingContext.ci].settings.contentWidth==='site' ? 'active' : ''" class="premium-btn py-2.5 rounded-md text-[10px] font-bold uppercase transition-all">Site Width</button>
                </div>
            </div>

            {{-- Field 2: Container Height --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Container Height</label>
                <select x-model="layout[editingContext.ci].settings.height" class="premium-input appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlPSIjOTA0RkZGIiBzdHJva2Utd2lkdGg9IjIiPjxwYXRoIGQ9Ik0xOSA5bC03IDctNy03Ii8+PC9zdmc+')] bg-[length:16px] bg-[right_12px_center] bg-no-repeat font-bold text-[11px]">
                    <option value="auto">Auto (Content Based)</option>
                    <option value="full">Window Height (100vh)</option>
                    <option value="min">Minimum Height (Custom)</option>
                </select>
            </div>

            {{-- Field 3: Min Height --}}
            <div class="space-y-3" x-show="layout[editingContext.ci].settings.height === 'min'">
                <label class="block text-[11px] font-bold text-slate-500 uppercase">Min Height (Pixels)</label>
                <input type="number" x-model.number="layout[editingContext.ci].settings.minHeight" class="premium-input border-dashed">
            </div>

            {{-- Field 4: Row Alignment (Vertical - Align Content) --}}
            <div class="space-y-3" x-show="layout[editingContext.ci].settings.height !== 'auto'">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Row Alignment (Vertical)</label>
                    <i class="fa fa-question-circle text-slate-300 text-[10px] cursor-help"></i>
                </div>
                <div class="grid grid-cols-6 gap-1.5">
                    <template x-for="opt in [
                        {v: 'flex-start', l: 'Top', i: 'M4 4h16M4 8h16'},
                        {v: 'center', l: 'Center', i: 'M4 12h16M7 8h10M7 16h10'},
                        {v: 'flex-end', l: 'Bottom', i: 'M4 20h16M4 16h16'},
                        {v: 'stretch', l: 'Stretch', i: 'M4 4v16M20 4v16M8 4h8M8 20h8'},
                        {v: 'space-between', l: 'Between', i: 'M4 4h16M4 20h16M4 12h8'},
                        {v: 'space-around', l: 'Around', i: 'M4 4h16M4 20h16M8 12h8'}
                    ]" :key="opt.v">
                        <button @click="layout[editingContext.ci].settings.rowAlign = opt.v" :class="layout[editingContext.ci].settings.rowAlign === opt.v ? 'active' : ''" class="premium-btn h-11 rounded flex items-center justify-center group relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path :d="opt.i" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <div class="setting-tooltip" x-text="opt.l"></div>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Field 5: Column Alignment (Vertical Cross Axis) --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Column Alignment (Vertical)</label>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="opt in [
                        {v: 'flex-start', l: 'Align Top', i: 'M4 4h16M8 8v12M16 8v8'},
                        {v: 'center', l: 'Align Center', i: 'M4 12h16M8 8v8M16 10v4'},
                        {v: 'flex-end', l: 'Align Bottom', i: 'M4 20h16M8 4v12M16 10v6'},
                        {v: 'stretch', l: 'Align Stretch', i: 'M4 4h16M4 20h16M8 4v16M16 4v16'}
                    ]" :key="opt.v">
                        <button @click="layout[editingContext.ci].settings.align = opt.v" :class="layout[editingContext.ci].settings.align === opt.v ? 'active' : ''" class="premium-btn h-12 rounded-lg flex items-center justify-center group relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path :d="opt.i" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <div class="setting-tooltip" x-text="opt.l"></div>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Field 6: Column Justification (Horizontal Main Axis) --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Column Justification</label>
                    <i class="fa fa-question-circle text-slate-300 text-[10px] cursor-help"></i>
                </div>
                <div class="grid grid-cols-6 gap-1.5">
                    <template x-for="opt in [
                        {v: 'flex-start', l: 'Left', i: 'M4 4v16M8 8h10M13 12h5'},
                        {v: 'center', l: 'Center', i: 'M12 4v16M7 8h10M9 12h6'},
                        {v: 'flex-end', l: 'Right', i: 'M20 4v16M6 8h10M6 12h10'},
                        {v: 'space-between', l: 'Between', i: 'M4 4v16M20 4v16M8 8h3M13 8h3'},
                        {v: 'space-around', l: 'Around', i: 'M4 4v16M20 4v16M7 8h2M15 8h2'},
                        {v: 'space-evenly', l: 'Evenly', i: 'M4 4v16M20 4v16M6 8h1M11 8h1M16 8h1'}
                    ]" :key="opt.v">
                        <button @click="layout[editingContext.ci].settings.justify = opt.v" :class="layout[editingContext.ci].settings.justify === opt.v ? 'active' : ''" class="premium-btn h-11 rounded flex items-center justify-center group relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path :d="opt.i" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <div class="setting-tooltip" x-text="opt.l"></div>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Field 7: Column Spacing (Gap) --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Column Spacing (Gap)</label>
                <input type="range" x-model.number="layout[editingContext.ci].settings.columnSpacing" min="0" max="100" class="w-full accent-[#135E96]">
                <div class="flex justify-between text-[10px] font-black text-slate-400"><span x-text="layout[editingContext.ci].settings.columnSpacing + ' PX'"></span><span>100 PX</span></div>
            </div>

            {{-- Field 8: HTML Tag --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">HTML Tag</label>
                <select x-model="layout[editingContext.ci].settings.htmlTag" class="premium-input appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlPSIjOTA0RkZGIiBzdHJva2Utd2lkdGg9IjIiPjxwYXRoIGQ9Ik0xOSA5bC03IDctNy03Ii8+PC9zdmc+')] bg-[length:16px] bg-[right_12px_center] bg-no-repeat font-bold text-[11px]">
                    <option value="div">div</option><option value="section">section</option><option value="header">header</option><option value="footer">footer</option>
                </select>
            </div>

            {{-- Field 9: Menu Anchor --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Menu Anchor</label>
                <input type="text" x-model="layout[editingContext.ci].settings.menuAnchor" placeholder="e.g. services" class="premium-input">
            </div>

            {{-- Field 10/11: Visibility --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Visibility</label>
                <div class="flex bg-slate-100 p-1 rounded-lg gap-1">
                    <template x-for="mode in ['mobile', 'tablet', 'desktop']">
                        <button @click="layout[editingContext.ci].settings.visibility[mode] = !layout[editingContext.ci].settings.visibility[mode]" :class="layout[editingContext.ci].settings.visibility[mode] ? 'active' : ''" class="premium-btn flex-1 h-10 flex items-center justify-center rounded-md group relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path x-show="mode==='mobile'" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                <path x-show="mode==='tablet'" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                <path x-show="mode==='desktop'" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <div class="setting-tooltip" x-text="mode.charAt(0).toUpperCase() + mode.slice(1)"></div>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>

{{-- Column General Settings Partial --}}
<div class="space-y-7 pb-12" x-show="editingContext.type === 'column' && activeSubTab === 'general'">
    @php
        $col = "layout[editingContext.ci]?.columns[editingContext.coli]";
    @endphp

    <template x-if="editingContext.type === 'column' && {{ $col }}">
        <div class="space-y-7">
            {{-- 1. Alignment (Self-Alignment) --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Alignment</label>
                    <i class="fa fa-question-circle text-slate-300 text-[10px] cursor-help"></i>
                </div>
                <div class="grid grid-cols-4 gap-1.5">
                    <template x-for="opt in [
                        {v: 'flex-start', l: 'Top', i: 'M4 4h16M8 8v12M16 8v8'},
                        {v: 'center', l: 'Middle', i: 'M4 12h16M8 8v8M16 10v4'},
                        {v: 'flex-end', l: 'Bottom', i: 'M4 20h16M8 4v12M16 10v6'},
                        {v: 'stretch', l: 'Stretch', i: 'M4 4h16M4 20h16M8 4v16M16 4v16'}
                    ]" :key="opt.l">
                        <button @click="{{ $col }}.settings.align = opt.v" 
                                :class="{{ $col }}.settings.align === opt.v ? 'active' : ''" 
                                class="premium-btn aspect-square rounded flex items-center justify-center group relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path :d="opt.i" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <div class="setting-tooltip" x-text="opt.l"></div>
                        </button>
                    </template>
                </div>
            </div>

            {{-- 2. Content Layout --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Content Layout</label>
                    <i class="fa fa-question-circle text-slate-300 text-[10px] cursor-help"></i>
                </div>
                <div class="flex bg-slate-100 p-0.5 rounded gap-0.5">
                    <template x-for="opt in ['Column', 'Row', 'Block']">
                        <button @click="{{ $col }}.settings.contentLayout = opt.toLowerCase()" 
                                :class="{{ $col }}.settings.contentLayout === opt.toLowerCase() ? 'bg-white text-[#135E96] shadow-sm' : 'text-slate-400 hover:text-slate-600'" 
                                class="flex-1 py-1.5 text-[11px] font-black rounded transition-all" x-text="opt"></button>
                    </template>
                </div>
            </div>

            {{-- 3. Content Alignment --}}
            <div class="space-y-4" x-show="{{ $col }}.settings.contentLayout !== 'block'" x-transition:enter="duration-200">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Content Alignment</label>
                    <i class="fa fa-question-circle text-slate-300 text-[10px] cursor-help"></i>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="opt in ({{ $col }}.settings.contentLayout === 'row' ? [
                        {v: 'flex-start', l: 'Left', i: 'M6 4v16 M10 8h8 M10 12h5'},
                        {v: 'center', l: 'Center', i: 'M12 4v16 M8 8h8 M9 12h6'},
                        {v: 'flex-end', l: 'Right', i: 'M18 4v16 M6 8h8 M9 12h5'},
                        {v: 'space-between', l: 'Between', i: 'M4 4v16 M20 4v16 M9 12h6'},
                        {v: 'space-around', l: 'Around', i: 'M4 4v16 M20 4v16 M6 12h2 M16 12h2'},
                        {v: 'space-evenly', l: 'Evenly', i: 'M4 4v16 M20 4v16 M8 12h1 M12 12h1 M16 12h1'}
                    ] : [
                        {v: 'flex-start', l: 'Top', i: 'M4 6h16 M8 10h8 M10 14h4'},
                        {v: 'center', l: 'Middle', i: 'M8 10h8 M4 6h16 M4 18h16'},
                        {v: 'flex-end', l: 'Bottom', i: 'M4 18h16 M8 14h8 M10 10h4'},
                        {v: 'space-between', l: 'Between', i: 'M4 6h16 M4 18h16 M8 12h8'},
                        {v: 'space-around', l: 'Around', i: 'M4 6h16 M4 18h16 M8 9h8 M8 15h8'},
                        {v: 'space-evenly', l: 'Evenly', i: 'M4 6h16 M4 18h16 M8 10h8 M8 14h8 M8 12h8'}
                    ])" :key="opt.l">
                        <button @click="{{ $col }}.settings.contentAlign = opt.v" 
                                :class="{{ $col }}.settings.contentAlign === opt.v ? 'active' : ''" 
                                class="premium-btn py-3.5 rounded flex items-center justify-center group relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path :d="opt.i" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <div class="setting-tooltip" x-text="opt.l"></div>
                        </button>
                    </template>
                </div>
            </div>

            {{-- 4. Content Vertical Alignment --}}
            <div class="space-y-4" x-show="{{ $col }}.settings.contentLayout === 'row'" x-transition:enter="duration-200">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Content Vertical Alignment</label>
                    <i class="fa fa-question-circle text-slate-300 text-[10px] cursor-help"></i>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="opt in [
                        {v: 'flex-start', l: 'Top', i: 'M4 4h16 M6 8v6 M14 8v3'},
                        {v: 'center', l: 'Center', i: 'M4 12h16 M6 8v8 M14 10v4'},
                        {v: 'flex-end', l: 'Bottom', i: 'M4 20h16 M6 10v10 M14 13v7'},
                        {v: 'stretch', l: 'Stretch', i: 'M4 4h16 M4 20h16 M6 4v16 M14 4v16'}
                    ]" :key="opt.l">
                        <button @click="{{ $col }}.settings.contentVAlign = opt.v" 
                                :class="{{ $col }}.settings.contentVAlign === opt.v ? 'active' : ''" 
                                class="premium-btn h-12 rounded flex items-center justify-center group relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path :d="opt.i" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <div class="setting-tooltip" x-text="opt.l"></div>
                        </button>
                    </template>
                </div>
            </div>

            {{-- 5. HTML Tag --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Column HTML Tag</label>
                <select x-model="{{ $col }}.settings.htmlTag" class="premium-input appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlPSIjOTA0RkZGIiBzdHJva2Utd2lkdGg9IjIiPjxwYXRoIGQ9Ik0xOSA5bC03IDctNy03Ii8+PC9zdmc+')] bg-[length:14px] bg-[right_12px_center] bg-no-repeat font-bold text-[11px]">
                    <option value="div">Default</option><option value="section">section</option><option value="article">article</option><option value="header">header</option><option value="footer">footer</option><option value="main">main</option>
                </select>
            </div>

            {{-- 6. Link URL --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Link URL</label>
                <input type="text" x-model="{{ $col }}.settings.linkUrl" placeholder="Select Link" class="premium-input border-dashed">
            </div>

            {{-- 7. Column Visibility --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Column Visibility</label>
                <div class="flex bg-[#135E96] rounded overflow-hidden shadow-sm">
                    <template x-for="mode in [
                        {id: 'mobile', i: 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', l: 'Small Screen'},
                        {id: 'tablet', i: 'M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', l: 'Medium Screen'},
                        {id: 'desktop', i: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', l: 'Large Screen'}
                    ]" :key="mode.id">
                        <button @click="{{ $col }}.settings.visibility[mode.id] = !{{ $col }}.settings.visibility[mode.id]" 
                                :class="{{ $col }}.settings.visibility[mode.id] ? 'bg-[#135E96] text-white' : 'bg-slate-200 text-slate-400'" 
                                class="flex-1 h-10 flex items-center justify-center border-r border-white/10 last:border-0 transition-colors group relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path :d="mode.i" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </template>
                </div>
            </div>

            {{-- 8. CSS --}}
            <div class="space-y-3">
                <input type="text" x-model="{{ $col }}.settings.cssClass" placeholder="Custom CSS Class" class="premium-input border-dashed">
                <input type="text" x-model="{{ $col }}.settings.cssId" placeholder="Custom CSS ID" class="premium-input border-dashed">
            </div>
        </div>
    </template>
</div>
