{{-- Navigator View Partial --}}
<div class="flex flex-col h-full bg-white overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#135E96]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
            <span class="font-black text-slate-800 uppercase text-[10px] tracking-[0.2em]">Navigator</span>
        </div>
    </div>
    <div class="flex-1 overflow-y-auto p-3 space-y-1 custom-scrollbar">
        <template x-for="(container, ci) in layout" :key="container.id">
            <div class="mb-3">
                <div class="nav-item-row group" :class="activeCi === ci ? 'active' : ''" @@click="scrollTo(ci)">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="nav-label">Container</span>
                    <div class="nav-actions">
                        <button @@click.stop="activeCi=ci; activeTab='settings'; editingContext={type:'container', ci:ci}" class="nav-btn"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                        <button @@click.stop="cloneContainer(ci)" class="nav-btn"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg></button>
                        <button @@click.stop="layout.splice(ci,1)" class="nav-btn danger"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                    </div>
                </div>
                <div class="ml-4 border-l-2 border-slate-100 pl-2 mt-1 space-y-1">
                    <template x-for="(column, coli) in container.columns" :key="column.id">
                        <div class="nav-item-row group !py-1.5" :class="(activeColi === coli && activeColCi === ci) ? 'active' : ''" @@click="activeColi=coli; activeColCi=ci; activeCi=null">
                            <svg class="w-3.5 h-3.5" :class="(activeColi === coli && activeColCi === ci) ? 'text-amber-500' : 'text-slate-300 group-hover:text-amber-500'" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM14 9a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V9z"/></svg>
                            <span class="text-[12px] font-bold">Column</span>
                            <div class="nav-actions">
                                <button @@click.stop="activeColi=coli; activeColCi=ci; activeTab='settings'; editingContext={type:'column', ci:ci, coli:coli}" class="nav-btn"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                <button @@click.stop="deleteColumn(ci, coli)" class="nav-btn danger"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
