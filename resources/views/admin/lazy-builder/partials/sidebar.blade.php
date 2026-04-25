<aside class="builder-sidebar flex flex-col" v-if="!isPreview">
    <!-- Mini Tab Icons at Top (WP/Avada Style) -->
    <div class="flex border-b border-slate-100 bg-slate-50/50">
        <button @click="activeTab='settings'" :class="activeTab==='settings' ? 'bg-white border-b-2 border-[#0091ea] text-[#0091ea]' : 'text-slate-400'" class="w-12 h-12 flex items-center justify-center transition-all">
            <i class="fa fa-cog text-sm"></i>
        </button>
        <button @click="activeTab='elements'" :class="activeTab==='elements' ? 'bg-white border-b-2 border-[#0091ea] text-[#0091ea]' : 'text-slate-400'" class="w-12 h-12 flex items-center justify-center transition-all">
            <i class="fa fa-th text-sm"></i>
        </button>
        <div class="flex-1 flex items-center px-4">
             <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                <i class="fa fa-caret-down"></i> Navigator
             </span>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="flex-1 overflow-y-auto">
        <!-- Elements Tab -->
        <div v-show="activeTab==='elements'" class="p-4 animate-fade-in">
            <div class="grid grid-cols-2 gap-2">
                <div v-for="el in availableElements" :key="el.type" 
                     class="p-4 bg-white border border-slate-100 rounded hover:border-[#0091ea] hover:shadow-sm transition-all cursor-pointer text-center group"
                     @click="addElement(el)">
                    <div class="w-10 h-10 bg-slate-50 rounded flex items-center justify-center mx-auto mb-2 group-hover:bg-blue-50 transition-colors">
                        <i :class="el.icon" class="text-slate-400 group-hover:text-[#0091ea] transition-colors"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase">@{{ el.name }}</span>
                </div>
            </div>
        </div>

        <!-- Navigator Tab (Matches Screenshot) -->
        <div v-show="activeTab==='navigator'" class="animate-fade-in">
            <div v-if="layout.length === 0" class="flex flex-col items-center justify-center py-20 px-10 text-center bg-slate-50/30">
                <div class="w-14 h-14 bg-[#0091ea] rounded-lg shadow-xl shadow-blue-500/20 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-[12px] font-black text-slate-800 uppercase tracking-widest mb-2">Navigator</h3>
                <p class="text-[11px] text-slate-400 leading-relaxed font-medium">No content has been added to the post yet.</p>
            </div>
            <div v-else>
                <div v-for="(cont, ci) in layout" :key="cont.id" 
                     class="nav-item" :class="activeCi === ci ? 'active' : ''"
                     @click="activeCi = ci">
                    <i class="fa fa-layer-group text-xs text-slate-300"></i>
                    <span class="text-[11px] font-bold text-slate-600">Container #@{{ ci + 1 }}</span>
                </div>
            </div>
        </div>

        <!-- Settings Tab -->
        <div v-show="activeTab==='settings'" class="h-full animate-fade-in flex flex-col">
            <div v-if="editingContext.type === 'container'" class="h-full">
                @include('cms-dashboard::admin.lazy-builder.partials.components.container.edit-panel')
            </div>
            <div v-else-if="editingContext.type" class="p-6">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Editing @{{ editingContext.type }}</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded border border-dashed border-slate-200 text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Settings Coming Soon</p>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-20 px-10">
                <i class="fa fa-mouse-pointer text-4xl text-slate-100 mb-6"></i>
                <p class="text-[12px] font-medium text-slate-400">Select an element to edit</p>
            </div>
        </div>
    </div>
    
    <!-- Footer Logo/Version -->
    <div class="p-4 border-t border-slate-50 bg-slate-50/30 flex items-center justify-between">
        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Lazy Builder v3.0</span>
        <div class="flex gap-2">
             <i class="fa fa-cog text-[10px] text-slate-300"></i>
             <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
        </div>
    </div>
</aside>
