@php $c = "layout[editingContext.ci].columns[editingContext.coli].settings"; @endphp

<div class="h-full flex flex-col bg-white">
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
        <h3 class="text-[13px] font-bold text-[#444]">Column</h3>
        <div class="flex gap-2">
            <button class="text-slate-400 hover:text-slate-600"><i class="fa fa-ellipsis-h text-[10px]"></i></button>
            <button @click="editingContext.type = null" class="text-slate-400 hover:text-red-500"><i class="fa fa-times text-[10px]"></i></button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex bg-[#0091ea]">
        <button @click="activeColPanelTab = 'general'"
                :class="activeColPanelTab === 'general' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'"
                class="flex-1 py-2 text-[12px] font-bold transition-colors">
            <i class="fa fa-sliders-h"></i>
        </button>
        <button @click="activeColPanelTab = 'design'"
                :class="activeColPanelTab === 'design' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'"
                class="flex-1 py-2 text-[10px] font-bold transition-colors">Design</button>
    </div>

    <!-- Panel body -->
    <div class="flex-1 overflow-y-auto p-4"
         v-if="layout[editingContext.ci] && layout[editingContext.ci].columns[editingContext.coli]">

        <!-- ══ GENERAL TAB ══ -->
        <div v-show="activeColPanelTab === 'general'" class="space-y-6">

            <!-- Alignment (align-self) -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Alignment</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    <button @click="{{ $c }}.alignment = 'flex-start'"
                            :class="{{ $c }}.alignment === 'flex-start' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="5" y="4" width="3" height="10" rx="0.5"/>
                            <rect x="10.5" y="4" width="3" height="14" rx="0.5"/>
                            <rect x="16" y="4" width="3" height="8" rx="0.5"/>
                        </svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Top</div>
                    </button>
                    <button @click="{{ $c }}.alignment = 'center'"
                            :class="{{ $c }}.alignment === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="5" y="7" width="3" height="10" rx="0.5"/>
                            <rect x="10.5" y="5" width="3" height="14" rx="0.5"/>
                            <rect x="16" y="8" width="3" height="8" rx="0.5"/>
                        </svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Center</div>
                    </button>
                    <button @click="{{ $c }}.alignment = 'flex-end'"
                            :class="{{ $c }}.alignment === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="5" y="10" width="3" height="10" rx="0.5"/>
                            <rect x="10.5" y="6" width="3" height="14" rx="0.5"/>
                            <rect x="16" y="12" width="3" height="8" rx="0.5"/>
                        </svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Bottom</div>
                    </button>
                    <button @click="{{ $c }}.alignment = 'stretch'"
                            :class="{{ $c }}.alignment === 'stretch' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 3l3 3h-2v12h2l-3 3-3-3h2V6H9l3-3z"/>
                        </svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Stretch</div>
                    </button>
                </div>
            </div>

            <!-- Content Layout -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Content Layout</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button @click="{{ $c }}.contentLayout = 'column'"
                            :class="{{ $c }}.contentLayout === 'column' || !{{ $c }}.contentLayout ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors text-[11px] font-semibold">
                        Column
                    </button>
                    <button @click="{{ $c }}.contentLayout = 'row'"
                            :class="{{ $c }}.contentLayout === 'row' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors text-[11px] font-semibold">
                        Row
                    </button>
                    <button @click="{{ $c }}.contentLayout = 'block'"
                            :class="{{ $c }}.contentLayout === 'block' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors text-[11px] font-semibold">
                        Block
                    </button>
                </div>
            </div>

            <!-- Content Alignment: Column mode -->
            <div v-if="{{ $c }}.contentLayout === 'column'">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Content Alignment</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                {{-- Row 1: Horizontal (align-items, cross-axis) --}}
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <button @click="{{ $c }}.contentAlignH = 'flex-start'"
                            :class="{{ $c }}.contentAlignH === 'flex-start' || !{{ $c }}.contentAlignH ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="4" width="2" height="16" rx="0.5"/><rect x="7" y="7" width="5" height="10" rx="0.5"/><rect x="14" y="9" width="5" height="6" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Left</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignH = 'center'"
                            :class="{{ $c }}.contentAlignH === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="11" y="3" width="2" height="18" rx="0.5"/><rect x="6" y="7" width="5" height="10" rx="0.5"/><rect x="13" y="9" width="5" height="6" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Center</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignH = 'flex-end'"
                            :class="{{ $c }}.contentAlignH === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="19" y="4" width="2" height="16" rx="0.5"/><rect x="8" y="7" width="5" height="10" rx="0.5"/><rect x="14" y="9" width="4" height="6" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Right</div>
                    </button>
                </div>
                {{-- Row 2: Vertical (justify-content, main-axis) --}}
                <div class="grid grid-cols-3 gap-2">
                    <button @click="{{ $c }}.contentAlignV = 'flex-start'"
                            :class="{{ $c }}.contentAlignV === 'flex-start' || !{{ $c }}.contentAlignV ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="3" width="16" height="2" rx="0.5"/><rect x="7" y="7" width="10" height="4" rx="0.5"/><rect x="9" y="13" width="6" height="4" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Top</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignV = 'center'"
                            :class="{{ $c }}.contentAlignV === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="11" width="16" height="2" rx="0.5"/><rect x="7" y="5" width="10" height="4" rx="0.5"/><rect x="9" y="15" width="6" height="4" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Middle</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignV = 'flex-end'"
                            :class="{{ $c }}.contentAlignV === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="19" width="16" height="2" rx="0.5"/><rect x="7" y="9" width="10" height="4" rx="0.5"/><rect x="9" y="7" width="6" height="4" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Bottom</div>
                    </button>
                </div>
            </div>

            <!-- Content Alignment: Row mode -->
            <div v-if="{{ $c }}.contentLayout === 'row'">
                {{-- Section 1: Content Alignment (justify-content, main-axis H) --}}
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Content Alignment</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <button @click="{{ $c }}.contentAlignH = 'flex-start'"
                            :class="{{ $c }}.contentAlignH === 'flex-start' || !{{ $c }}.contentAlignH ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="4" width="2" height="16" rx="0.5"/><rect x="7" y="7" width="5" height="10" rx="0.5"/><rect x="14" y="9" width="5" height="6" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Start</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignH = 'center'"
                            :class="{{ $c }}.contentAlignH === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="11" y="3" width="2" height="18" rx="0.5"/><rect x="6" y="7" width="5" height="10" rx="0.5"/><rect x="13" y="9" width="5" height="6" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Center</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignH = 'flex-end'"
                            :class="{{ $c }}.contentAlignH === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="19" y="4" width="2" height="16" rx="0.5"/><rect x="8" y="7" width="5" height="10" rx="0.5"/><rect x="14" y="9" width="4" height="6" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">End</div>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <button @click="{{ $c }}.contentAlignH = 'space-between'"
                            :class="{{ $c }}.contentAlignH === 'space-between' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="7" width="3" height="10" rx="0.5"/><rect x="10.5" y="9" width="3" height="6" rx="0.5"/><rect x="18" y="7" width="3" height="10" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Between</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignH = 'space-around'"
                            :class="{{ $c }}.contentAlignH === 'space-around' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4.5" y="7" width="3" height="10" rx="0.5"/><rect x="10.5" y="9" width="3" height="6" rx="0.5"/><rect x="16.5" y="7" width="3" height="10" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Around</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignH = 'space-evenly'"
                            :class="{{ $c }}.contentAlignH === 'space-evenly' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="7" width="3" height="10" rx="0.5"/><rect x="10.5" y="9" width="3" height="6" rx="0.5"/><rect x="16" y="7" width="3" height="10" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Evenly</div>
                    </button>
                </div>

                {{-- Section 2: Content Vertical Alignment (align-items, cross-axis V) --}}
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Content Vertical Alignment</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <button @click="{{ $c }}.contentAlignV = 'flex-start'"
                            :class="{{ $c }}.contentAlignV === 'flex-start' || !{{ $c }}.contentAlignV ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="3" width="16" height="2" rx="0.5"/><rect x="7" y="7" width="10" height="4" rx="0.5"/><rect x="9" y="13" width="6" height="4" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Top</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignV = 'center'"
                            :class="{{ $c }}.contentAlignV === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="11" width="16" height="2" rx="0.5"/><rect x="7" y="5" width="10" height="4" rx="0.5"/><rect x="9" y="15" width="6" height="4" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Middle</div>
                    </button>
                    <button @click="{{ $c }}.contentAlignV = 'flex-end'"
                            :class="{{ $c }}.contentAlignV === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="19" width="16" height="2" rx="0.5"/><rect x="7" y="9" width="10" height="4" rx="0.5"/><rect x="9" y="7" width="6" height="4" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Bottom</div>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button @click="{{ $c }}.contentAlignV = 'stretch'"
                            :class="{{ $c }}.contentAlignV === 'stretch' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="3" width="16" height="2" rx="0.5"/><rect x="4" y="19" width="16" height="2" rx="0.5"/><rect x="7" y="7" width="10" height="10" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Stretch</div>
                    </button>
                </div>
            </div>

            <!-- Gap -->
            <div v-if="{{ $c }}.contentLayout === 'column' || {{ $c }}.contentLayout === 'row'">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Gap</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Width</label>
                        <input type="number" v-model.number="{{ $c }}.gapWidth" placeholder="px"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Height</label>
                        <input type="number" v-model.number="{{ $c }}.gapHeight" placeholder="px"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
            </div>

            <!-- Column HTML Tag -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Column HTML Tag</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <select v-model="{{ $c }}.htmlTag"
                        class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    <option value="div">Default (div)</option>
                    <option value="article">article</option>
                    <option value="section">section</option>
                    <option value="aside">aside</option>
                    <option value="header">header</option>
                    <option value="footer">footer</option>
                </select>
            </div>

            <!-- Link URL -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Link URL</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <div class="relative">
                    <input type="text" v-model="{{ $c }}.linkUrl" placeholder="https://"
                           class="w-full border border-slate-200 rounded px-2 py-1.5 pr-7 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    <i class="fa fa-link absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                </div>
            </div>

            <!-- Column Visibility -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Column Visibility</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2"
                     @click.capture="if (!{{ $c }}.visibility) { {{ $c }}.visibility = { mobile: true, tablet: true, desktop: true }; }">
                    <button @click="{{ $c }}.visibility.mobile = !{{ $c }}.visibility.mobile"
                            :class="{{ $c }}.visibility && {{ $c }}.visibility.mobile !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-mobile-alt text-[12px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Mobile</div>
                    </button>
                    <button @click="{{ $c }}.visibility.tablet = !{{ $c }}.visibility.tablet"
                            :class="{{ $c }}.visibility && {{ $c }}.visibility.tablet !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-tablet-alt text-[12px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Tablet</div>
                    </button>
                    <button @click="{{ $c }}.visibility.desktop = !{{ $c }}.visibility.desktop"
                            :class="{{ $c }}.visibility && {{ $c }}.visibility.desktop !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-desktop text-[12px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Desktop</div>
                    </button>
                </div>
            </div>

            <!-- CSS Class -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">CSS Class</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <input type="text" v-model="{{ $c }}.cssClass"
                       class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
            </div>

            <!-- CSS ID -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">CSS ID</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <input type="text" v-model="{{ $c }}.cssId"
                       class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
            </div>

        </div><!-- /general -->

        <!-- ══ DESIGN TAB ══ -->
        <div v-show="activeColPanelTab === 'design'" class="space-y-6">

            <!-- Margin -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Margin</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-desktop text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-1">
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top</label>
                        <input type="number" v-model.number="{{ $c }}.marginTop" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Right</label>
                        <input type="number" v-model.number="{{ $c }}.marginRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bottom</label>
                        <input type="number" v-model.number="{{ $c }}.marginBottom" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Left</label>
                        <input type="number" v-model.number="{{ $c }}.marginLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                </div>
            </div>

            <!-- Padding -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Padding</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-desktop text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-1">
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top</label>
                        <input type="number" v-model.number="{{ $c }}.paddingTop" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Right</label>
                        <input type="number" v-model.number="{{ $c }}.paddingRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bottom</label>
                        <input type="number" v-model.number="{{ $c }}.paddingBottom" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Left</label>
                        <input type="number" v-model.number="{{ $c }}.paddingLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                </div>
            </div>

            <!-- Text Color -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Text Color</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-cog text-[10px]"></i>
                        <i class="fa fa-undo text-[10px]"></i>
                        <i class="fa fa-circle text-[10px] text-white border border-slate-300 rounded-full"></i>
                        <i class="fa fa-database text-[10px]"></i>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <input type="color" :value="{{ $c }}.textColor || '#000000'" @input="{{ $c }}.textColor = $event.target.value" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                    <div class="relative flex-1">
                        <input type="text" v-model="{{ $c }}.textColor" placeholder="#000000"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Background Color -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Background Color</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-cog text-[10px]"></i>
                        <i class="fa fa-undo text-[10px]"></i>
                        <i class="fa fa-desktop text-[10px]"></i>
                        <i class="fa fa-database text-[10px]"></i>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <input type="color" :value="{{ $c }}.bgColor || '#ffffff'" @input="{{ $c }}.bgColor = $event.target.value" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                    <div class="relative flex-1">
                        <input type="text" v-model="{{ $c }}.bgColor" placeholder="transparent"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Typography -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <label class="text-[11px] font-bold text-[#444]">Typography</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-cog text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Size (px)</label>
                        <input type="number" v-model.number="{{ $c }}.fontSize" placeholder="16"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Weight</label>
                        <select v-model="{{ $c }}.fontWeight" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <option value="">Default</option>
                            <option value="300">Light 300</option>
                            <option value="400">Regular 400</option>
                            <option value="500">Medium 500</option>
                            <option value="600">Semi-Bold 600</option>
                            <option value="700">Bold 700</option>
                            <option value="800">Extra-Bold 800</option>
                        </select></div>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Line Height</label>
                        <input type="number" step="0.1" v-model.number="{{ $c }}.lineHeight" placeholder="1.5"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Letter Spacing</label>
                        <input type="number" step="0.1" v-model.number="{{ $c }}.letterSpacing" placeholder="0"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                </div>
                <!-- Text Align -->
                <div class="grid grid-cols-4 gap-2">
                    <button @click="{{ $c }}.textAlign = 'left'"
                            :class="{{ $c }}.textAlign === 'left' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-align-left text-[11px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Left</div>
                    </button>
                    <button @click="{{ $c }}.textAlign = 'center'"
                            :class="{{ $c }}.textAlign === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-align-center text-[11px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Center</div>
                    </button>
                    <button @click="{{ $c }}.textAlign = 'right'"
                            :class="{{ $c }}.textAlign === 'right' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-align-right text-[11px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Right</div>
                    </button>
                    <button @click="{{ $c }}.textAlign = 'justify'"
                            :class="{{ $c }}.textAlign === 'justify' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-align-justify text-[11px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Justify</div>
                    </button>
                </div>
            </div>

            <!-- Border Size -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Border Size</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-cog text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-1">
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top</label>
                        <input type="number" v-model.number="{{ $c }}.borderSizeTop" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Right</label>
                        <input type="number" v-model.number="{{ $c }}.borderSizeRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bottom</label>
                        <input type="number" v-model.number="{{ $c }}.borderSizeBottom" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Left</label>
                        <input type="number" v-model.number="{{ $c }}.borderSizeLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                </div>
            </div>

            <!-- Border Color (conditional) -->
            <div v-if="{{ $c }}.borderSizeTop > 0 || {{ $c }}.borderSizeRight > 0 || {{ $c }}.borderSizeBottom > 0 || {{ $c }}.borderSizeLeft > 0">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Border Color</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-database text-[10px]"></i>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <input type="color" v-model="{{ $c }}.borderColor" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                    <div class="relative flex-1">
                        <input type="text" v-model="{{ $c }}.borderColor"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Border Radius -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Border Radius</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-1">
                    <div><label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top/Left</label>
                        <input type="number" v-model.number="{{ $c }}.borderRadiusTopLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top/Right</label>
                        <input type="number" v-model.number="{{ $c }}.borderRadiusTopRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bot/Right</label>
                        <input type="number" v-model.number="{{ $c }}.borderRadiusBottomRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    <div><label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bot/Left</label>
                        <input type="number" v-model.number="{{ $c }}.borderRadiusBottomLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                </div>
            </div>

            <!-- Box Shadow -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Box Shadow</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                <div class="flex w-[100px] bg-slate-100 rounded overflow-hidden">
                    <button @click="{{ $c }}.boxShadow = true"
                            :class="{{ $c }}.boxShadow ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Yes</button>
                    <button @click="{{ $c }}.boxShadow = false"
                            :class="!{{ $c }}.boxShadow ? 'bg-slate-200 text-slate-500' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[10px] font-bold transition-colors">No</button>
                </div>
            </div>

            <template v-if="{{ $c }}.boxShadow">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Shadow Position</label>
                        <div class="flex gap-2 text-slate-300">
                            <i class="fa fa-question-circle text-[10px]"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Vertical</label>
                            <input type="number" v-model.number="{{ $c }}.boxShadowPositionVertical" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                        <div><label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Horizontal</label>
                            <input type="number" v-model.number="{{ $c }}.boxShadowPositionHorizontal" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Blur Radius</label>
                        <div class="flex gap-2 text-slate-300">
                            <i class="fa fa-question-circle text-[10px]"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="number" v-model.number="{{ $c }}.boxShadowBlurRadius" class="w-16 border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <input type="range" v-model.number="{{ $c }}.boxShadowBlurRadius" min="0" max="100" class="flex-1 accent-[#0091ea]">
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Spread Radius</label>
                        <div class="flex gap-2 text-slate-300">
                            <i class="fa fa-question-circle text-[10px]"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="number" v-model.number="{{ $c }}.boxShadowSpreadRadius" class="w-16 border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <input type="range" v-model.number="{{ $c }}.boxShadowSpreadRadius" min="0" max="50" class="flex-1 accent-[#0091ea]">
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Shadow Color</label>
                        <div class="flex gap-2 text-slate-300">
                            <i class="fa fa-question-circle text-[10px]"></i>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center">
                        <input type="color" v-model="{{ $c }}.boxShadowColor" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                        <input type="text" v-model="{{ $c }}.boxShadowColor"
                               class="flex-1 border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Shadow Style</label>
                        <div class="flex gap-2 text-slate-300">
                            <i class="fa fa-question-circle text-[10px]"></i>
                        </div>
                    </div>
                    <div class="flex w-[140px] bg-slate-100 rounded overflow-hidden">
                        <button @click="{{ $c }}.boxShadowStyle = 'outer'"
                                :class="{{ $c }}.boxShadowStyle === 'outer' || !{{ $c }}.boxShadowStyle ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Outer</button>
                        <button @click="{{ $c }}.boxShadowStyle = 'inner'"
                                :class="{{ $c }}.boxShadowStyle === 'inner' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Inner</button>
                    </div>
                </div>
            </template>

        </div><!-- /design -->

    </div>
</div>
