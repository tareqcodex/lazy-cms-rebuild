<div x-show="showElementModal"
     x-cloak
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm"
     @@keydown.escape.window="showElementModal=false">

    <div class="bg-white w-full shadow-2xl flex flex-col" style="max-width:1100px; height:85vh; border-radius:2px;" 
         x-data="{ elementTab: 'design', search: '' }"
         @@click.away="showElementModal=false">
        
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-[#2c3338] text-white shrink-0">
            <h3 class="text-[14px] font-bold uppercase tracking-wider">Select Element</h3>
            <div class="flex items-center gap-4">
                {{-- Search --}}
                <div class="relative group">
                    <input type="text" x-model="search" placeholder="Search Elements" class="bg-[#1d2327] border border-[#dcdcde]/10 text-[12px] px-8 py-2 rounded-sm w-64 outline-none focus:border-[#2271b1] transition-all">
                    <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button @@click="showElementModal=false" class="hover:text-red-400 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex bg-[#135E96] px-2 shrink-0 overflow-x-auto custom-scrollbar">
            <template x-for="tab in [
                {id: 'design', label: 'Design Elements'},
                {id: 'library', label: 'Library Elements'},
                {id: 'nested', label: 'Nested Columns'},
                {id: 'studio', label: 'Studio'}
            ]">
                <button @@click="elementTab = tab.id"
                        :class="elementTab === tab.id ? 'bg-white text-[#135E96]' : 'text-white/80 hover:bg-white/10'"
                        class="px-6 py-3 text-[11px] font-black uppercase tracking-widest transition-all whitespace-nowrap"
                        x-text="tab.label"></button>
            </template>
        </div>

        {{-- Content Area --}}
        <div class="flex-1 overflow-y-auto p-8 bg-[#f6f7f7]">
            
            {{-- Design Elements Tab --}}
            <div x-show="elementTab === 'design'" class="animate-in fade-in zoom-in-95 duration-200">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    {{-- Heading Element --}}
                    <div @@click="insertElement('heading', 'Heading')"
                         class="cursor-pointer flex flex-col items-center gap-4 group bg-white p-6 border border-transparent hover:border-[#135E96] hover:shadow-xl transition-all rounded-sm text-center">
                        <div class="w-12 h-12 flex items-center justify-center bg-slate-100 rounded-full group-hover:bg-[#135E96] group-hover:text-white transition-colors text-slate-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M5 4v3h5.5v12h3V7H19V4H5z"/></svg>
                        </div>
                        <span class="text-[11px] text-slate-500 font-black tracking-widest uppercase group-hover:text-[#135E96]">Title / Heading</span>
                    </div>

                    {{-- Text Element --}}
                    <div @@click="insertElement('text', 'Text Block')"
                         class="cursor-pointer flex flex-col items-center gap-4 group bg-white p-6 border border-transparent hover:border-[#135E96] hover:shadow-xl transition-all rounded-sm text-center">
                        <div class="w-12 h-12 flex items-center justify-center bg-slate-100 rounded-full group-hover:bg-[#135E96] group-hover:text-white transition-colors text-slate-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 9h16v2H4V9zm0 4h10v2H4v-2zM4 5h16v2H4V5zm0 12h16v2H4v-2z"/></svg>
                        </div>
                        <span class="text-[11px] text-slate-500 font-black tracking-widest uppercase group-hover:text-[#135E96]">Text Block</span>
                    </div>
                </div>
            </div>

            {{-- Nested Columns Tab (Conditional: Forbidden if already inside a nested column) --}}
            <div x-show="elementTab === 'nested' && !elementContext.isNested" class="animate-in fade-in zoom-in-95 duration-200">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-7 gap-6">
                    <template x-for="(preset, pi) in columnPresets" :key="pi">
                        {{-- Only allow nested columns if it's not a deeply nested column? User said 1 level only. --}}
                        <div @@click="insertNestedColumns(preset)"
                             class="cursor-pointer flex flex-col items-center gap-3 group bg-white p-3 border border-transparent hover:border-[#135E96] hover:shadow-xl transition-all rounded-sm">
                            
                            <div class="w-full h-16 flex gap-1.5 p-1 bg-slate-50 border border-slate-200 group-hover:bg-white transition-colors">
                                <template x-for="(box, bi) in preset.boxes" :key="bi">
                                    <div class="rounded-[1px] bg-slate-300 group-hover:bg-[#135E96] transition-all"
                                         :style="`flex: ${box}; border: 1px dashed rgba(255,255,255,0.3);`"></div>
                                </template>
                            </div>
                            
                            <span class="text-[10px] text-slate-500 font-black tracking-tighter uppercase group-hover:text-[#135E96]" x-text="preset.label"></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Placeholder for other tabs --}}
            <div x-show="elementTab === 'library' || elementTab === 'studio'" class="flex flex-col items-center justify-center py-20 text-slate-400 animate-in fade-in duration-300">
                <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="text-[11px] font-black uppercase tracking-widest" x-text="elementTab + ' coming soon...'"></p>
            </div>

        </div>

    </div>
</div>
