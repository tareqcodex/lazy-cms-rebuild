{{-- Container Extras Settings Partial --}}
<div class="space-y-8 pb-10" x-show="editingContext.type === 'container' && activeSubTab === 'extras'">
    <template x-if="layout[editingContext.ci]">
        <div class="space-y-8">
            {{-- ── POSITION ABSOLUTE ── --}}
            <div class="space-y-4">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-widest flex items-center justify-between">
                    Position Absolute
                </label>
                <div class="flex bg-slate-100 p-1 rounded-sm gap-1 w-32">
                    <button @click="layout[editingContext.ci].settings.posAbsolute = true" :class="layout[editingContext.ci].settings.posAbsolute ? 'bg-[#135E96] text-white shadow-sm' : 'text-slate-500'" class="flex-1 py-1.5 text-[9px] font-bold uppercase transition-all">On</button>
                    <button @click="layout[editingContext.ci].settings.posAbsolute = false" :class="!layout[editingContext.ci].settings.posAbsolute ? 'bg-white text-slate-400' : 'text-slate-500'" class="flex-1 py-1.5 text-[9px] font-bold uppercase transition-all">Off</button>
                </div>
            </div>

            {{-- ── POSITION STICKY ── --}}
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-widest">
                    Position Sticky
                </label>
                <div class="flex bg-slate-100 p-1 rounded-sm gap-1 w-32">
                    <button @click="layout[editingContext.ci].settings.posSticky = true" :class="layout[editingContext.ci].settings.posSticky ? 'bg-[#135E96] text-white shadow-sm' : 'text-slate-500'" class="flex-1 py-1.5 text-[9px] font-bold uppercase transition-all">On</button>
                    <button @click="layout[editingContext.ci].settings.posSticky = false" :class="!layout[editingContext.ci].settings.posSticky ? 'bg-white text-slate-400' : 'text-slate-500'" class="flex-1 py-1.5 text-[9px] font-bold uppercase transition-all">Off</button>
                </div>
            </div>
            
            <div class="space-y-4" x-show="layout[editingContext.ci].settings.posSticky">
                <label class="block text-[10px] font-bold text-slate-400 uppercase">Sticky Offset</label>
                <input type="number" x-model.number="layout[editingContext.ci].settings.stickyOffset" class="premium-input h-10 px-4 font-bold" placeholder="0">
            </div>
        </div>
    </template>
</div>
