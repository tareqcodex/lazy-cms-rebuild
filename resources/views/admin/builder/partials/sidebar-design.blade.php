{{-- Container Design Settings Partial --}}
<div class="space-y-8 pb-10" x-show="editingContext.type === 'container' && activeSubTab === 'design'">
    <template x-if="layout[editingContext.ci]">
        <div class="space-y-8">
            {{-- MARGIN --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Margin</label>
                <div class="grid grid-cols-2 gap-4">
                    <template x-for="side in ['marginTop','marginBottom']">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-slate-400 mb-1 uppercase" x-text="side === 'marginTop' ? 'Top' : 'Bottom'"></span>
                            <div class="flex">
                                <input type="number" x-model.number="layout[editingContext.ci].settings[side]" class="premium-input square-input flex-1 text-center">
                                <select class="unit-select" x-model="unit"><option>px</option><option>%</option><option>rem</option><option>em</option></select>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- PADDING --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Padding</label>
                <div class="grid grid-cols-2 gap-4">
                    <template x-for="side in ['Top','Right','Bottom','Left']">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-slate-400 mb-1 uppercase" x-text="side"></span>
                            <div class="flex">
                                <input type="number" x-model.number="layout[editingContext.ci].settings['padding'+side]" class="premium-input square-input flex-1 text-center px-1">
                                <select class="unit-select" x-model="unit"><option>px</option><option>%</option></select>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- LINK COLOR --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Link Color</label>
                <div class="flex gap-3">
                    <div class="w-10 h-10 border-2 border-slate-200 shadow-inner flex-shrink-0" :style="`background: ${layout[editingContext.ci].settings.linkColor || '#135E96'};`" @click="$refs.clrInp.click()"></div>
                    <input type="text" x-model="layout[editingContext.ci].settings.linkColor" class="premium-input square-input flex-1" placeholder="#135E96">
                    <input type="color" x-ref="clrInp" x-model="layout[editingContext.ci].settings.linkColor" class="hidden">
                </div>
            </div>

            {{-- BORDERS --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Borders (px)</label>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="side in ['Top','Right','Bottom','Left']">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-slate-400 mb-1 uppercase" x-text="side"></span>
                            <input type="number" x-model.number="layout[editingContext.ci].settings['border'+side]" class="premium-input square-input text-center">
                        </div>
                    </template>
                </div>
            </div>

            {{-- RADIUS --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Radius (px)</label>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="corner in ['TopLeft','TopRight','BotRight','BotLeft']">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-slate-400 mb-1 uppercase" x-text="corner"></span>
                            <input type="number" x-model.number="layout[editingContext.ci].settings['radius'+corner]" class="premium-input square-input text-center">
                        </div>
                    </template>
                </div>
            </div>

            {{-- BOX SHADOW --}}
            <div class="space-y-6">
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Box Shadow</label>
                    <div class="flex bg-slate-100 p-1">
                        <button @click="layout[editingContext.ci].settings.boxShadow = true" :class="layout[editingContext.ci].settings.boxShadow ? 'bg-[#135E96] text-white shadow-sm' : 'text-slate-500'" class="flex-1 py-1.5 text-[10px] font-bold uppercase transition-all">Yes</button>
                        <button @click="layout[editingContext.ci].settings.boxShadow = false" :class="!layout[editingContext.ci].settings.boxShadow ? 'bg-[#135E96] text-white shadow-sm' : 'text-slate-500'" class="flex-1 py-1.5 text-[10px] font-bold uppercase transition-all">No</button>
                    </div>
                </div>

                {{-- Expanded Shadow Options --}}
                <div x-show="layout[editingContext.ci].settings.boxShadow" x-transition.opacity class="space-y-6 pt-4 border-t border-slate-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-slate-400 mb-1 uppercase">Vertical</span>
                            <input type="number" x-model.number="layout[editingContext.ci].settings.shadowVertical" class="premium-input square-input text-center" placeholder="0">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-slate-400 mb-1 uppercase">Horizontal</span>
                            <input type="number" x-model.number="layout[editingContext.ci].settings.shadowHorizontal" class="premium-input square-input text-center" placeholder="0">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Blur & Spread</label>
                        <div class="flex gap-4">
                            <input type="number" x-model.number="layout[editingContext.ci].settings.shadowBlur" class="premium-input square-input w-20 text-center" placeholder="Blur">
                            <input type="number" x-model.number="layout[editingContext.ci].settings.shadowSpread" class="premium-input square-input w-20 text-center" placeholder="Spread">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Shadow Color</label>
                        <div class="flex gap-3">
                            <div class="w-10 h-10 border-2 border-slate-200 rounded-full" :style="`background: ${layout[editingContext.ci].settings.shadowColor || 'rgba(0,0,0,0.1)'};`" @click="$refs.shadowClr.click()"></div>
                            <input type="text" x-model="layout[editingContext.ci].settings.shadowColor" class="premium-input square-input flex-1" placeholder="rgba(0,0,0,0.1)">
                            <input type="color" x-ref="shadowClr" x-model="layout[editingContext.ci].settings.shadowColor" class="hidden">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Z INDEX --}}
            <div class="space-y-3 pt-6 border-t border-slate-100">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-wider">Z Index</label>
                <input type="number" x-model.number="layout[editingContext.ci].settings.zIndex" class="premium-input square-input" placeholder="Auto">
            </div>
        </div>
    </template>
</div>
