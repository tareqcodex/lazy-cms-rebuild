{{-- Container Background Settings Partial --}}
<div class="space-y-6 pb-10" x-show="editingContext.type === 'container' && activeSubTab === 'background'">
    <template x-if="layout[editingContext.ci]">
        <div class="space-y-6">
            {{-- Header Section --}}
            <div class="space-y-4">
                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-widest flex items-center justify-between">
                    Background Options
                    <svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                </label>
                
                {{-- Background Type Tabs --}}
                <div class="flex border border-slate-100 bg-[#fbfcfd] shadow-sm overflow-hidden rounded-sm">
                    <template x-for="type in [
                        {id:'color', l:'Color', i:'M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM7 7h10v10H7z'},
                        {id:'gradient', l:'Gradient', i:'M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 16H5l12-12v12z'},
                        {id:'image', l:'Image', i:'M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 9.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5zM5 17l3.5-4.5 2.5 3.01L14.5 11l4.5 6H5z'}
                    ]">
                        <button @click="layout[editingContext.ci].settings.bgType = type.id" 
                                :class="layout[editingContext.ci].settings.bgType === type.id ? 'active bg-white text-[#135E96] border-b-2 border-b-[#135E96]' : 'text-slate-300 hover:text-slate-500'"
                                class="flex-1 h-12 flex items-center justify-center border-r border-slate-100 last:border-0 relative group transition-all">
                            <svg class="w-5 h-5 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path :d="type.i"/></svg>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Views --}}
            <div x-show="layout[editingContext.ci].settings.bgType === 'color'" class="space-y-4">
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-700 uppercase">Background Color</label>
                    <div class="flex gap-3">
                        <div class="w-12 h-12 border-2 border-slate-100 shadow-inner flex-shrink-0" :style="`background: ${layout[editingContext.ci].settings.bgColor || '#ffffff'};`" @click="$refs.bgClrInp.click()"></div>
                        <input type="text" x-model="layout[editingContext.ci].settings.bgColor" class="premium-input flex-1 font-bold" placeholder="#FFFFFF">
                        <input type="color" x-ref="bgClrInp" x-model="layout[editingContext.ci].settings.bgColor" class="hidden">
                    </div>
                </div>
            </div>

            <div x-show="layout[editingContext.ci].settings.bgType === 'gradient'" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase text-center">Start</label>
                        <div class="w-full h-10 border border-slate-200" :style="`background: ${layout[editingContext.ci].settings.bgGradStart || '#135E96'};`" @click="$refs.gradSInp.click()"></div>
                        <input type="color" x-ref="gradSInp" x-model="layout[editingContext.ci].settings.bgGradStart" class="hidden">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase text-center">End</label>
                        <div class="w-full h-10 border border-slate-200" :style="`background: ${layout[editingContext.ci].settings.bgGradEnd || '#000000'};`" @click="$refs.gradEInp.click()"></div>
                        <input type="color" x-ref="gradEInp" x-model="layout[editingContext.ci].settings.bgGradEnd" class="hidden">
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Angle</label>
                    <input type="range" x-model.number="layout[editingContext.ci].settings.bgGradAngle" min="0" max="360" class="w-full accent-[#135E96]">
                </div>
            </div>

            <div x-show="layout[editingContext.ci].settings.bgType === 'image'" class="space-y-4">
                <div class="aspect-video bg-slate-50 border-2 border-dashed border-slate-200 rounded flex flex-col items-center justify-center cursor-pointer hover:border-[#135E96] transition-all" 
                     @click="openMedia({type:'container', ci:editingContext.ci})">
                    <template x-if="layout[editingContext.ci].settings.bgImage">
                        <img :src="layout[editingContext.ci].settings.bgImage" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!layout[editingContext.ci].settings.bgImage">
                        <div class="text-center space-y-2 p-4">
                            <div class="text-slate-300 font-bold uppercase text-[10px]">Select Image</div>
                        </div>
                    </template>
                </div>
                <template x-if="layout[editingContext.ci].settings.bgImage">
                    <div class="space-y-4">
                         <button @click="layout[editingContext.ci].settings.bgImage = ''" class="w-full py-2 bg-red-50 text-red-500 text-[10px] font-bold uppercase rounded">Remove Image</button>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>
