{{-- ═══════════════════════════════════════════════════
     Media Library Modal
     ═══════════════════════════════════════════════════ --}}
<div x-show="showMediaModal" 
     x-transition.opacity 
     class="fixed inset-0 z-[600] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
     x-cloak>
    
    <div @@click.away="showMediaModal = false" 
         class="bg-white w-full max-w-5xl h-[85vh] rounded-lg shadow-2xl flex flex-col overflow-hidden animate-in zoom-in-95 duration-200">
        
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-4">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800">Media Library</h3>
                <nav class="flex gap-4 ml-6">
                    <button class="text-[10px] font-black uppercase tracking-widest text-[#135E96] border-b-2 border-[#135E96] pb-1">All Media</button>
                    <button class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Upload New</button>
                </nav>
            </div>
            <button @@click="showMediaModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="flex-1 flex overflow-hidden">
            
            {{-- Main Grid --}}
            <div class="flex-1 overflow-y-auto p-6 bg-white custom-scrollbar">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <template x-for="img in mediaLibrary" :key="img.url">
                        <div class="relative aspect-square bg-slate-50 border-2 transition-all cursor-pointer group rounded-sm overflow-hidden"
                             :class="selectedMedia?.url === img.url ? 'border-[#135E96] ring-2 ring-blue-100' : 'border-transparent hover:border-slate-200'"
                             @@click="selectedMedia = img">
                            <img :src="img.url" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-[#135E96]/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            {{-- Checkmark for selected --}}
                            <div x-show="selectedMedia?.url === img.url" class="absolute top-1 right-1 bg-[#135E96] text-white rounded-full p-0.5 shadow-md">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Sidebar Details --}}
            <div class="w-72 border-l border-slate-100 bg-slate-50 p-6 flex flex-col overflow-y-auto custom-scrollbar">
                <template x-if="selectedMedia">
                    <div class="space-y-6">
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400">Media Details</h4>
                        <div class="aspect-video w-full bg-white border border-slate-200 shadow-sm overflow-hidden rounded-sm">
                            <img :src="selectedMedia.url" class="w-full h-full object-contain">
                        </div>
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase">File Name</label>
                                <p class="text-[11px] font-bold text-slate-700 truncate" x-text="selectedMedia.name"></p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase">Dimensions</label>
                                <p class="text-[11px] font-bold text-slate-700">1920 × 1080</p>
                            </div>
                        </div>
                        <div class="pt-6">
                            <button @@click="applyMedia()" 
                                    class="w-full py-3 bg-[#135E96] text-white font-black text-[10px] uppercase tracking-[0.2em] shadow-lg hover:bg-[#0f4a76] transition-all">
                                Select Image
                            </button>
                        </div>
                    </div>
                </template>
                <template x-if="!selectedMedia">
                    <div class="h-full flex flex-col items-center justify-center text-center space-y-3 opacity-40">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-[10px] font-bold uppercase tracking-widest">Select an image to see details</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
