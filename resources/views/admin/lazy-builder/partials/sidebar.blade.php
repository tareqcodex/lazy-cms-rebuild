<aside class="builder-sidebar flex flex-col" v-if="!isPreview">
    <!-- Mini Tab Icons at Top (WP/Avada Style) -->
    <div class="flex border-b border-slate-100 bg-slate-50/50">
        <button @click="activeTab='settings'" :class="activeTab==='settings' ? 'bg-white border-b-2 border-[#0091ea] text-[#0091ea]' : 'text-slate-400'" class="w-12 h-12 flex items-center justify-center transition-all">
            <i class="fa fa-cog text-sm"></i>
        </button>
        <button @click="activeTab='navigator'" :class="activeTab==='navigator' ? 'bg-white border-b-2 border-[#0091ea] text-[#0091ea]' : 'text-slate-400'" class="flex-1 flex items-center justify-center gap-2 transition-all group">
             <i class="fa fa-caret-down text-[10px] text-slate-400 group-hover:text-[#0091ea]"></i>
             <span class="text-[11px] font-black uppercase tracking-widest text-slate-500 group-hover:text-[#0091ea]">Navigator</span>
        </button>
    </div>

    <!-- Tab Content -->
    <div class="flex-1 overflow-y-auto custom-scrollbar bg-white">
        <!-- Elements Tab (Removed) -->

        <!-- Navigator Tab -->
        <div v-show="activeTab==='navigator'" class="animate-fade-in py-2">
            <div v-if="layout.length === 0" class="flex flex-col items-center justify-center py-20 px-10 text-center bg-slate-50/30">
                <div class="w-14 h-14 bg-[#0091ea] rounded-lg shadow-xl shadow-blue-500/20 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-[12px] font-black text-slate-800 uppercase tracking-widest mb-2">Navigator</h3>
                <p class="text-[11px] text-slate-400 leading-relaxed font-medium">No content has been added yet.</p>
            </div>
            
            <div v-else class="space-y-0.5">
                <!-- Container Loop -->
                <div v-for="(cont, ci) in layout" :key="cont.id" class="group/nav">
                    <!-- Container Row -->
                    <div class="flex items-center gap-2 px-4 py-2 hover:bg-blue-50/50 cursor-pointer group/line"
                         :class="editingContext.type === 'container' && editingContext.ci === ci ? 'bg-blue-50' : ''"
                         @click="editingContext={type:'container', ci:ci}; activeTab='settings'">
                        <i class="fa fa-caret-down text-[10px] text-slate-400"></i>
                        <span class="text-[12px] font-bold text-[#0091ea] flex-1">Container</span>
                        <div class="flex items-center gap-2 opacity-0 group-hover/line:opacity-100 transition-opacity">
                            <i @click.stop="openColumnModal(ci)" class="fa fa-plus text-[9px] text-slate-400 hover:text-[#0091ea]" title="Add Column"></i>
                            <i @click.stop="editingContext={type:'container', ci:ci}; activeTab='settings'" class="fa fa-pen text-[9px] text-slate-400 hover:text-[#0091ea]" title="Edit"></i>
                            <i @click.stop="duplicateContainer(ci)" class="fa fa-copy text-[9px] text-slate-400 hover:text-[#0091ea]" title="Duplicate"></i>
                            <i @click.stop="layout.splice(ci, 1)" class="fa fa-trash-alt text-[9px] text-slate-400 hover:text-red-500" title="Delete"></i>
                        </div>
                    </div>

                    <!-- Column Loop -->
                    <div v-for="(col, coli) in cont.columns" :key="col.id" class="ml-6 border-l border-slate-100">
                        <div class="flex items-center gap-2 px-4 py-1.5 hover:bg-slate-50 cursor-pointer group/line"
                             :class="editingContext.type === 'column' && editingContext.ci === ci && editingContext.coli === coli ? 'bg-slate-50 border-l-2 border-[#0091ea] -ml-[1px]' : ''"
                             @click="editingContext={type:'column', ci:ci, coli:coli}; activeTab='settings'">
                            <i class="fa fa-caret-down text-[10px] text-slate-300"></i>
                            <span class="text-[11px] font-semibold text-slate-700 flex-1">Column @{{ formatBasisToFraction(col.basis) }}</span>
                            <div class="flex items-center gap-2 opacity-0 group-hover/line:opacity-100 transition-opacity">
                                <i @click.stop="openElementModal(ci, coli)" class="fa fa-plus text-[9px] text-slate-400 hover:text-[#0091ea]" title="Add Element"></i>
                                <i @click.stop="editingContext={type:'column', ci:ci, coli:coli}; activeTab='settings'" class="fa fa-pen text-[9px] text-slate-400 hover:text-[#0091ea]" title="Edit"></i>
                                <i @click.stop="duplicateColumn(ci, coli)" class="fa fa-copy text-[9px] text-slate-400 hover:text-[#0091ea]" title="Duplicate"></i>
                                <i @click.stop="cont.columns.splice(coli, 1)" class="fa fa-trash-alt text-[9px] text-slate-400 hover:text-red-500" title="Delete"></i>
                            </div>
                        </div>

                        <!-- Elements Loop -->
                        <div v-for="(el, eli) in col.elements" :key="el.id" class="ml-6 border-l border-slate-50">
                            <!-- Standard Element -->
                            <div v-if="el.type !== 'row'" 
                                 class="flex items-center gap-3 px-4 py-1.5 hover:bg-slate-50 cursor-pointer group/line"
                                 @click="editingContext={type:'element', ci:ci, coli:coli, eli:eli}; activeTab='settings'">
                                <i :class="el.icon" class="text-[11px] text-slate-400 w-4 text-center"></i>
                                <span class="text-[11px] text-slate-500 flex-1 capitalize">@{{ el.type }}</span>
                                <div class="flex items-center gap-2 opacity-0 group-hover/line:opacity-100 transition-opacity">
                                    <i @click.stop="openElementModal(ci, coli, 'design', false, eli + 1)" class="fa fa-plus text-[9px] text-slate-400 hover:text-[#0091ea]" title="Add Below"></i>
                                    <i @click.stop="editingContext={type:'element', ci:ci, coli:coli, eli:eli}; activeTab='settings'" class="fa fa-pen text-[9px] text-slate-400 hover:text-[#0091ea]" title="Edit"></i>
                                    <i @click.stop="duplicateElement(ci, coli, eli)" class="fa fa-copy text-[9px] text-slate-400 hover:text-[#0091ea]" title="Duplicate"></i>
                                    <i @click.stop="col.elements.splice(eli, 1)" class="fa fa-trash-alt text-[9px] text-slate-400 hover:text-red-500" title="Delete"></i>
                                </div>
                            </div>

                            <!-- Nested Row (Nested Columns) -->
                            <div v-else class="space-y-0.5">
                                <div class="flex items-center gap-2 px-4 py-1.5 hover:bg-slate-50 cursor-pointer group/line"
                                     @click="editingContext={type:'nested-row', ci:ci, coli:coli, eli:eli}; activeTab='settings'">
                                    <i class="fa fa-caret-down text-[10px] text-slate-400"></i>
                                    <span class="text-[11px] font-bold text-slate-600 flex-1">Nested Row</span>
                                    <div class="flex items-center gap-2 opacity-0 group-hover/line:opacity-100 transition-opacity">
                                        <i @click.stop="openElementModal(ci, coli, 'design', false, eli + 1)" class="fa fa-plus text-[9px] text-slate-400 hover:text-[#0091ea]" title="Add Below"></i>
                                        <i @click.stop="openElementModal(ci, coli, 'nested', true, eli)" class="fa fa-plus-square text-[9px] text-slate-400 hover:text-[#0091ea]" title="Add Nested Column"></i>
                                        <i @click.stop="editingContext={type:'nested-row', ci:ci, coli:coli, eli:eli}; activeTab='settings'" class="fa fa-pen text-[9px] text-slate-400 hover:text-[#0091ea]" title="Edit"></i>
                                        <i @click.stop="duplicateElement(ci, coli, eli)" class="fa fa-copy text-[9px] text-slate-400 hover:text-[#0091ea]" title="Duplicate"></i>
                                        <i @click.stop="col.elements.splice(eli, 1)" class="fa fa-trash-alt text-[9px] text-slate-400 hover:text-red-500" title="Delete"></i>
                                    </div>
                                </div>
                                <!-- Nested Column Loop -->
                                <div v-for="(ncol, ncoli) in el.columns" :key="ncol.id" class="ml-6 border-l border-slate-100">
                                    <div class="flex items-center gap-2 px-4 py-1.5 hover:bg-slate-50 cursor-pointer group/line"
                                         @click="editingContext={type:'nested-column', ci:ci, coli:coli, eli:eli, ncoli:ncoli}; activeTab='settings'">
                                        <i class="fa fa-caret-down text-[10px] text-slate-300"></i>
                                        <span class="text-[10px] font-bold text-slate-500 flex-1">Nested Column</span>
                                        <div class="flex items-center gap-2 opacity-0 group-hover/line:opacity-100 transition-opacity">
                                            <i @click.stop="openElementModal(ci, coli, 'design', true, eli, ncoli)" class="fa fa-plus text-[9px] text-slate-400 hover:text-[#0091ea]" title="Add Nested Element"></i>
                                            <i @click.stop="editingContext={type:'nested-column', ci:ci, coli:coli, eli:eli, ncoli:ncoli}; activeTab='settings'" class="fa fa-pen text-[9px] text-slate-400 hover:text-[#0091ea]" title="Edit"></i>
                                            <i @click.stop="duplicateNestedColumn(ci, coli, eli, ncoli)" class="fa fa-copy text-[9px] text-slate-400 hover:text-[#0091ea]" title="Duplicate"></i>
                                            <i @click.stop="el.columns.splice(ncoli, 1)" class="fa fa-trash-alt text-[9px] text-slate-400 hover:text-red-500" title="Delete"></i>
                                        </div>
                                    </div>
                                    <!-- Nested Elements -->
                                    <div v-for="(nel, neli) in ncol.elements" :key="nel.id" class="ml-6 border-l border-slate-50">
                                        <div class="flex items-center gap-3 px-4 py-1 hover:bg-slate-50 cursor-pointer group/line"
                                             @click="editingContext={type:'element', ci:ci, coli:coli, eli:eli, ncoli:ncoli, neli:neli}; activeTab='settings'">
                                            <i :class="nel.icon" class="text-[10px] text-slate-400 w-4 text-center"></i>
                                            <span class="text-[10px] text-slate-500 flex-1 capitalize">@{{ nel.type }}</span>
                                            <div class="flex items-center gap-2 opacity-0 group-hover/line:opacity-100 transition-opacity">
                                                <i @click.stop="openElementModal(ci, coli, 'design', true, eli, ncoli, neli + 1)" class="fa fa-plus text-[9px] text-slate-400 hover:text-[#0091ea]" title="Add Below"></i>
                                                <i @click.stop="editingContext={type:'element', ci:ci, coli:coli, eli:eli, ncoli:ncoli, neli:neli}; activeTab='settings'" class="fa fa-pen text-[9px] text-slate-400 hover:text-[#0091ea]" title="Edit"></i>
                                                <i @click.stop="duplicateNestedElement(ci, coli, eli, ncoli, neli)" class="fa fa-copy text-[9px] text-slate-400 hover:text-[#0091ea]" title="Duplicate"></i>
                                                <i @click.stop="ncol.elements.splice(neli, 1)" class="fa fa-trash-alt text-[9px] text-slate-400 hover:text-red-500" title="Delete"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Tab -->
        <div v-show="activeTab==='settings'" class="h-full animate-fade-in flex flex-col">
            <div v-if="editingContext.type === 'container'" class="h-full">
                @include('cms-dashboard::admin.lazy-builder.partials.components.container.edit-panel')
            </div>
            <div v-else-if="editingContext.type === 'nested-row'" class="h-full">
                @include('cms-dashboard::admin.lazy-builder.partials.components.container.edit-panel', ['isNestedRow' => true])
            </div>
            <div v-else-if="editingContext.type === 'column' || editingContext.type === 'nested-column'" class="h-full">
                @include('cms-dashboard::admin.lazy-builder.partials.components.column.edit-panel')
            </div>
            <div v-else-if="editingContext.type === 'element'" class="h-full">
                <!-- Dynamic Element Settings Panel -->
                <div class="flex flex-col h-full bg-white">
                    <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-[#0091ea] rounded flex items-center justify-center text-white shadow-sm">
                                <i :class="editingElement?.icon || 'fa fa-cube'" class="text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-[11px] font-black uppercase tracking-widest text-slate-800">@{{ editingElement?.name || 'Element' }} Settings</h3>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Edit Content & Design</p>
                            </div>
                        </div>
                        <button @click="activeTab='navigator'" class="text-slate-400 hover:text-[#0091ea] transition-colors">
                            <i class="fa fa-times text-sm"></i>
                        </button>
                    </div>

                    <div class="flex border-b border-slate-100 bg-slate-50/30">
                        <button @click="editingContext.tab = 'content'" 
                                :class="(editingContext.tab || 'content') === 'content' ? 'border-b-2 border-[#0091ea] text-[#0091ea] bg-white' : 'text-slate-400 hover:text-slate-600'" 
                                class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest transition-all">
                            Content
                        </button>
                        <button @click="editingContext.tab = 'design'" 
                                :class="editingContext.tab === 'design' ? 'border-b-2 border-[#0091ea] text-[#0091ea] bg-white' : 'text-slate-400 hover:text-slate-600'" 
                                class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest transition-all">
                            Design
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scrollbar p-4 bg-white">
                        <div v-if="(editingContext.tab || 'content') === 'content'">
                            <component :is="editingElement?.settingsComponent" :settings="editingElement?.settings"></component>
                        </div>
                        <div v-else-if="editingContext.tab === 'design'">
                             <!-- Basic Design Settings Placeholder -->
                             <div class="space-y-4">
                                 <div class="p-3 bg-blue-50 border border-blue-100 rounded text-[10px] text-blue-600 font-medium">
                                     Design settings for this element will appear here.
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
