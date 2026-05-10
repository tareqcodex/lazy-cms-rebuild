<div x-show="showModal"
     x-cloak
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     id="col-modal"
     @@keydown.escape.window="showModal=false">

    <div class="bg-white w-full shadow-2xl flex flex-col" style="max-width:900px; max-height:90vh; border-radius:2px;" @@click.away="showModal=false">

        {{-- Modal header --}}
        <div class="flex items-center justify-between px-5 py-3.5 flex-shrink-0" style="background:#222;">
            <h3 class="text-[13px] font-bold text-white tracking-wide">Select Column</h3>
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="flex items-center gap-2 bg-white/10 rounded px-2 py-1">
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search Columns" class="bg-transparent text-[11px] text-white placeholder-slate-400 outline-none w-28">
                </div>
                <button @@click="showModal=false" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Tab bar --}}
        <div class="flex border-b border-slate-200 flex-shrink-0" style="background:#2ea2cc;">
            <button class="px-5 py-2.5 text-[11px] font-bold text-white border-b-2 border-white uppercase tracking-wider">Builder Columns</button>
            <button class="px-5 py-2.5 text-[11px] font-semibold text-white/70 hover:text-white uppercase tracking-wider transition-colors">Library Columns</button>
            <button class="px-5 py-2.5 text-[11px] font-semibold text-white/70 hover:text-white uppercase tracking-wider transition-colors">Studio</button>
        </div>

        {{-- Column grid --}}
        <div class="overflow-y-auto flex-1 p-6 bg-[#f9f9f9]">
            <div class="grid gap-4" style="grid-template-columns: repeat(7, 1fr);">
                <template x-for="(preset, pi) in columnPresets" :key="pi">
                    <div @@click="addContainer(preset)"
                         class="cursor-pointer flex flex-col items-center gap-2 group">
                        <div class="w-full h-14 bg-white border border-slate-200 rounded p-1.5 flex gap-1 group-hover:border-[#2ea2cc] group-hover:shadow-md transition-all">
                            <template x-for="(box, bi) in preset.boxes" :key="bi">
                                <div class="rounded-sm group-hover:bg-[#2ea2cc] transition-colors flex-shrink-0"
                                     :style="`flex: ${box}; background: #b8bfc7;`"></div>
                            </template>
                        </div>
                        <span class="text-[8px] text-slate-500 font-semibold text-center leading-tight whitespace-nowrap" x-text="preset.label"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
