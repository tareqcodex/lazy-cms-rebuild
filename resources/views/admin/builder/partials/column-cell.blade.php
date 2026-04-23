{{-- ════════════════════════════════════════════════
     Column Cell  (used inside x-for: column, coli)
     ════════════════════════════════════════════════ --}}
<div class="col-cell w-full flex flex-col transition-none"
     :style="{
        paddingTop: (column.settings.marginTop || 0) + 'px',
        paddingBottom: (column.settings.marginBottom || 0) + 'px',
        paddingLeft: (column.settings.marginLeft || 0) + '%',
        paddingRight: (column.settings.marginRight || 0) + '%'
     }"
     style="min-height: 120px; position: relative; overflow: visible;">

    <style>
        .col-border-box { border: 2px solid #2ea2cc; }
        .col-active .col-avada-toolbar { opacity: 1; pointer-events: auto; }
        
        .col-avada-toolbar {
            position: absolute; bottom: 100%; left: 0;
            background: #2ea2cc; display: flex; align-items: center;
            border-radius: 4px; padding: 2px 8px; gap: 8px;
            opacity: 0; pointer-events: none; transition: opacity .2s;
            z-index: 250; box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            height: 34px; margin-bottom: 4px;
        }

        .col-avada-btn {
            display: flex; align-items: center; justify-content: center;
            color: #fff; background: transparent; border-radius: 3px; position: relative;
            transition: background.2s; cursor: pointer; height: 26px; min-width: 26px;
        }
        .col-avada-btn:hover { background: rgba(255,255,255,0.15); }
        .col-avada-btn.txt { font-size: 11px; font-weight: 700; color: #fff; padding: 0 4px; }

        .col-margin-overlay { background: rgba(155, 89, 182, 0.08); transition: background .2s; }
        .col-spacing-active .col-margin-overlay:hover { background: rgba(155, 89, 182, 0.15) !important; }
        .col-padding-overlay { background: rgba(46, 162, 204, 0.08); transition: background .2s; }
        .col-spacing-active .col-padding-overlay:hover { background: rgba(46, 162, 204, 0.15) !important; }
    </style>

    <div class="col-border-box w-full relative bg-white flex flex-col transition-none overflow-visible cursor-default" 
         :class="[
            (activeColi === coli && activeColCi === ci) ? '!border-[#2ea2cc] col-active' : 'border-transparent hover:border-[#2ea2cc15]',
            ((column.settings.align || container.settings.align || 'stretch') === 'stretch') ? 'flex-1' : ''
         ]"
         style="min-height:120px;"
         @@click.stop="activeColi = coli; activeColCi = ci; activeCi = null; editingContext = {type: 'column', ci: ci, coli: coli}"

        {{-- COLUMN TOOLBAR --}}
        <div class="col-avada-toolbar">
            <div class="col-avada-btn" @@click.stop="showSpacing('column', ci, coli)"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg></div>
            <div class="col-avada-btn" @@click.stop="openColumnModal(ci, coli)"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-4H7v-2h4V7h2v4h4v2h-4v4z"/></svg></div>
            <div class="col-avada-btn txt" x-text="column.frac"></div>
            <div class="col-avada-btn" @@click="cloneColumn(ci, coli)"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></div>
            <div class="col-avada-btn" @@click="deleteColumn(ci, coli)"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></div>
            <div class="col-avada-btn cursor-move"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M10 9h4V6h3l-5-5-5 5h3v3zm-1 1H6V7l-5 5 5 5v-3h3v-4zm14 2l-5-5v3h-3v4h3v3l5-5zm-9 3h-4v3H7l5 5 5-5h-3v-3z"/></svg></div>
        </div>

        {{-- VISIBILITY WRAPPER --}}
        <div x-show="spacingEditor.type === 'column' && spacingEditor.ci === ci && spacingEditor.coli === coli" class="col-spacing-active">
            <div class="col-margin-overlay absolute left-0 right-0" :style="`top: -${column.settings.marginTop || 0}px; height: ${column.settings.marginTop || 0}px;`" x-show="column.settings.marginTop > 0"></div>
            <div class="col-margin-overlay absolute left-0 right-0" :style="`bottom: -${column.settings.marginBottom || 0}px; height: ${column.settings.marginBottom || 0}px;`" x-show="column.settings.marginBottom > 0"></div>
            <div class="col-padding-overlay absolute top-0 left-0 right-0" :style="`height: ${column.settings.paddingTop || 0}px;`" x-show="column.settings.paddingTop > 0"></div>
            <div class="col-padding-overlay absolute bottom-0 left-0 right-0" :style="`height: ${column.settings.paddingBottom || 0}px;`" x-show="column.settings.paddingBottom > 0"></div>
            <div class="col-padding-overlay absolute top-0 bottom-0 left-0" :style="`width: ${column.settings.paddingLeft || 0}px;`" x-show="column.settings.paddingLeft > 0"></div>
            <div class="col-padding-overlay absolute top-0 bottom-0 right-0" :style="`width: ${column.settings.paddingRight || 0}px;`" x-show="column.settings.paddingRight > 0"></div>

            {{-- HANDLES --}}
            <div class="absolute left-1/2 -translate-x-full z-[300]" :style="`top: 0px; transform: translate(-100%, -50%);`"><button class="w-5 h-5 flex items-center justify-center bg-[#9b59b6] text-white rounded shadow-sm" @@mousedown.prevent="dragStart($event, ci, 'margin', 'top', coli)"><svg class="w-2.5" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg></button></div>
            <div class="absolute left-1/2 translate-x-0 z-[300]" :style="`top: ${column.settings.paddingTop || 0}px; transform: translate(0, -50%);`"><button class="w-5 h-5 flex items-center justify-center bg-[#2ea2cc] text-white rounded shadow-sm" @@mousedown.prevent="dragStart($event, ci, 'padding', 'top', coli)"><svg class="w-2.5" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg></button></div>
            <div class="absolute left-1/2 translate-x-0 z-[300]" :style="`bottom: -${column.settings.marginBottom || 0}px; transform: translate(0, 50%);`"><button class="w-5 h-5 flex items-center justify-center bg-[#9b59b6] text-white rounded shadow-sm" @@mousedown.prevent="dragStart($event, ci, 'margin', 'bottom', coli)"><svg class="w-2.5" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg></button></div>
            <div class="absolute left-1/2 -translate-x-full z-[300]" :style="`bottom: 0px; transform: translate(-100%, 50%);`"><button class="w-5 h-5 flex items-center justify-center bg-[#2ea2cc] text-white rounded shadow-sm" @@mousedown.prevent="dragStart($event, ci, 'padding', 'bottom', coli)"><svg class="w-2.5" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg></button></div>
            <div class="absolute left-0 top-1/2 -translate-y-full z-[300]" :style="`left: 0px; transform: translate(-50%, -100%);`"><button class="w-5 h-5 flex items-center justify-center bg-[#9b59b6] text-white rounded shadow-sm" @@mousedown.prevent="dragStart($event, ci, 'margin', 'left', coli)"><svg class="w-2.5 rotate-90" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg></button></div>
            <div class="absolute left-0 top-1/2 translate-y-0 z-[300]" :style="`left: ${column.settings.paddingLeft || 0}px; transform: translate(-50%, 0);`"><button class="w-5 h-5 flex items-center justify-center bg-[#2ea2cc] text-white rounded shadow-sm" @@mousedown.prevent="dragStart($event, ci, 'padding', 'left', coli)"><svg class="w-2.5 rotate-90" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg></button></div>
            <div class="absolute right-0 top-1/2 -translate-y-full z-[300]" :style="`right: ${column.settings.paddingRight || 0}px; transform: translate(50%, -100%);`"><button class="w-5 h-5 flex items-center justify-center bg-[#2ea2cc] text-white rounded shadow-sm" @@mousedown.prevent="dragStart($event, ci, 'padding', 'right', coli)"><svg class="w-2.5 rotate-90" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg></button></div>
            <div class="absolute right-0 top-1/2 translate-y-0 z-[300]" :style="`right: 0px; transform: translate(50%, 0);`"><button class="w-5 h-5 flex items-center justify-center bg-[#9b59b6] text-white rounded shadow-sm" @@mousedown.prevent="dragStart($event, ci, 'margin', 'right', coli)"><svg class="w-2.5 rotate-90" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg></button></div>
        </div>

        {{-- COLUMN CONTENT --}}
        <div class="flex-1 relative overflow-visible" 
             :id="column.settings.cssId"
             :class="[
                column.settings.cssClass,
                column.settings.contentLayout === 'block' ? 'block' : 'flex',
                column.settings.contentLayout === 'row' ? 'flex-row' : '',
                column.settings.contentLayout === 'column' ? 'flex-col' : ''
             ]"
             :style="{
                padding: `${column.settings.paddingTop || 0}px ${column.settings.paddingRight || 0}px ${column.settings.paddingBottom || 0}px ${column.settings.paddingLeft || 0}px`,
                justifyContent: column.settings.contentAlign || 'flex-start',
                alignItems: (column.settings.contentLayout === 'row') ? (column.settings.contentVAlign || 'stretch') : 'stretch',
                gap: '1rem',
                minHeight: '120px'
             }">
             
 
             
            {{-- Loop Children (Elements or Nested Rows) --}}
            <template x-for="(child, chi) in (column.children || column.elements || [])" :key="child.id">
                <div class="relative group/el" :class="column.settings.contentLayout === 'row' ? 'w-fit max-w-full' : 'w-full'">
                    
                    {{-- 1. HEADING ELEMENT --}}
                    <template x-if="child.type === 'heading'">
                        <div class="p-4 border border-dashed border-transparent hover:border-[#135E96] transition-all relative">
                            <h1 x-show="child.settings.tag==='h1'" x-text="child.settings.title" :style="{ fontSize: child.settings.fontSize+'px', color: child.settings.color, textAlign: child.settings.textAlign, fontWeight: child.settings.fontWeight }"></h1>
                            <h2 x-show="child.settings.tag==='h2'" x-text="child.settings.title" :style="{ fontSize: child.settings.fontSize+'px', color: child.settings.color, textAlign: child.settings.textAlign, fontWeight: child.settings.fontWeight }"></h2>
                            <h3 x-show="child.settings.tag==='h3'" x-text="child.settings.title" :style="{ fontSize: child.settings.fontSize+'px', color: child.settings.color, textAlign: child.settings.textAlign, fontWeight: child.settings.fontWeight }"></h3>
                            
                            {{-- Element Toolbar --}}
                            <div class="absolute -top-3 right-0 hidden group-hover/el:flex bg-[#135E96] text-white px-2 py-1 rounded text-[9px] font-black uppercase items-center gap-2 z-20">
                                <span x-text="child.name"></span>
                                <button class="hover:text-white/80"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg></button>
                                <button @click.stop="(column.children || column.elements).splice(chi, 1)" class="hover:text-white/80"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></button>
                            </div>
                        </div>
                    </template>

                    {{-- 2. TEXT ELEMENT --}}
                    <template x-if="child.type === 'text'">
                        <div class="p-4 border border-dashed border-transparent hover:border-[#135E96] transition-all relative">
                            <div class="prose max-w-none" x-text="child.settings.content" :style="{ fontSize: child.settings.fontSize+'px', color: child.settings.color, textAlign: child.settings.textAlign, lineHeight: child.settings.lineHeight }"></div>
                            
                            {{-- Element Toolbar --}}
                            <div class="absolute -top-3 right-0 hidden group-hover/el:flex bg-[#135E96] text-white px-2 py-1 rounded text-[9px] font-black uppercase items-center gap-2 z-20">
                                <span x-text="child.name"></span>
                                <button class="hover:text-white/80"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg></button>
                                <button @click.stop="(column.children || column.elements).splice(chi, 1)" class="hover:text-white/80"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></button>
                            </div>
                        </div>
                    </template>

                    {{-- Case: Nested Row --}}
                    <template x-if="child.type === 'nested_row'">
                        <div class="w-full flex flex-row flex-wrap gap-4 py-6 px-1 relative group/nested border border-dashed border-[#e67e22]/40 hover:border-[#e67e22] transition-all bg-[#e67e22]/[0.02]">
                            {{-- Nested Column Loop --}}
                            <template x-for="(ncol, ncoli) in child.columns" :key="ncol.id">
                                <div :style="`flex: 0 0 calc(${ncol.basis}% - ${child.columns.length > 1 ? 16 : 0}px);`"
                                     class="min-h-[100px] bg-white border-2 border-transparent hover:border-[#e67e22]/50 transition-all flex flex-col relative group/ncol mt-6 mb-2">
                                     
                                     {{-- PREMIUM NESTED TOOLBAR --}}
                                     <div class="absolute bottom-100 left-0 bg-[#e67e22] text-white flex items-center h-[30px] px-2 gap-2.5 rounded-t-md opacity-0 group-hover/ncol:opacity-100 transition-all pointer-events-none group-hover/ncol:pointer-events-auto mb-[-1px] shadow-sm">
                                         <button class="hover:text-white/80 transition-colors cursor-pointer" title="Edit Nested Column"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg></button>
                                         <button @@click.stop="openElementModal(ci, coli, true)" class="hover:text-white/80 transition-colors cursor-pointer" title="Add Element"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg></button>
                                         <span class="text-[10px] font-black tracking-tighter mx-1" x-text="ncol.frac"></span>
                                         <button @@click.stop="duplicateNestedColumn(child, ncoli)" class="hover:text-white/80 transition-colors cursor-pointer" title="Clone Nested Column"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></button>
                                         <button @@click.stop="child.columns.splice(ncoli, 1)" class="hover:text-white/80 transition-colors cursor-pointer" title="Delete"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg></button>
                                         <button class="hover:text-white/80 transition-colors cursor-move" title="Move"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M10 9h4V6h3l-5-5-5 5h3v3zm-1 1H6V7l-5 5 5 5v-3h3v-4zm14 2l-5-5v3h-3v4h3v3l5-5zm-9 3h-4v3H7l5 5 5-5h-3v-3z"/></svg></button>
                                     </div>

                                     {{-- Nested Column Content Shell --}}
                                     <div class="flex-1 w-full p-4 border border-[#e67e22]/10 bg-white flex flex-col items-center justify-center min-h-[80px]">
                                         {{-- Nested Column Elements Loop --}}
                                         <div class="w-full flex flex-col gap-2">
                                             <template x-for="nchild in (ncol.children || ncol.elements || [])" :key="nchild.id">
                                                 <div class="p-2.5 bg-slate-50 border border-slate-100 text-[9px] font-black text-slate-500 uppercase text-center tracking-widest" x-text="nchild.name"></div>
                                             </template>
                                         </div>

                                         {{-- Placeholder when empty --}}
                                         <template x-if="(ncol.children || ncol.elements || []).length === 0">
                                            <div class="flex flex-col items-center opacity-20 py-4">
                                                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                                <span class="text-[8px] font-black uppercase tracking-widest">Add Element</span>
                                            </div>
                                         </template>
                                     </div>
                                </div>
                            </template>
                            {{-- Nested Toolbar Label --}}
                            <div class="absolute -top-3 left-3 bg-[#e67e22] text-white text-[8px] font-black px-3 py-0.5 rounded shadow-sm opacity-0 group-hover/nested:opacity-100 transition-all uppercase tracking-[0.2em]">Nested Columns</div>
                            
                            {{-- Delete Nested Row Button --}}
                            <button @@click.stop="column.children.splice(chi, 1)" class="absolute -top-3 right-3 bg-red-500 text-white p-1 rounded shadow-sm opacity-0 group-hover/nested:opacity-100"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                        </div>
                    </template>

                    {{-- Case: Element Placeholder --}}
                    <template x-if="child.type === 'element'">
                        <div class="p-4 bg-white border border-slate-100 shadow-sm text-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase" x-text="child.name"></span>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Dead Center Add Button (Overlay) --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-20">
                <button @@click.stop="openElementModal(ci, coli)" 
                        class="w-10 h-10 rounded-full flex items-center justify-center text-white shadow-xl pointer-events-auto transition-all transform hover:scale-110 active:scale-95 bg-[#2ea2cc]/90 hover:bg-[#2ea2cc]" 
                        style="box-shadow:0 8px 24px rgba(46,162,204,0.4);" title="Add Element">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>

            {{-- Link Overlay if linkUrl exists (Z-index 15 to stay above content but below tools) --}}
            <template x-if="column.settings.linkUrl">
                <a :href="column.settings.linkUrl" class="absolute inset-0 z-[15] cursor-pointer block border-none outline-none"></a>
            </template>
        </div>
    </div>
</div>
