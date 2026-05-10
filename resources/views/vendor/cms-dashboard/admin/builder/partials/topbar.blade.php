<header class="h-full bg-[#222] text-white flex items-center justify-between px-4 shadow-[0_2px_10px_rgba(0,0,0,.5)]">
    <!-- Left: Logo + utility icons -->
    <div class="flex items-center gap-0.5">
        <div class="flex items-center gap-1.5 mr-5">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2ea2cc" stroke-width="2.5">
                <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                <polyline points="2 17 12 22 22 17"/>
                <polyline points="2 12 12 17 22 12"/>
            </svg>
            <span class="text-[12px] font-bold tracking-tight">LAZY<span class="text-[#2ea2cc]">BUILDER</span></span>
        </div>

        <!-- Utility icon buttons -->
        <button title="Print" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        </button>
        <button title="History" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </button>
        <button title="Labels" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </button>
        <button @@click="showModal=true" title="Add Container" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </button>
        <button @@click="layout=[]" title="Clear All" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-400 hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </button>
    </div>

    <!-- Right: Help, devices, preview, save, close -->
    <div class="flex items-center gap-0.5">
        <button title="Help" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </button>
        <button title="Desktop" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </button>
        <button @@click="previewLayout()" title="Preview" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </button>
        <button title="Layers" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </button>

        <div class="w-px h-5 bg-white/10 mx-2"></div>

        <button @@click="saveLayout()" 
                :disabled="isSaving"
                :class="isSaving ? 'opacity-70 cursor-wait' : ''"
                class="bg-[#2ea2cc] hover:bg-[#2388ab] active:bg-[#1d7491] text-white text-[11px] font-bold px-6 py-1.5 transition-colors uppercase tracking-widest min-w-[120px]" style="letter-spacing:1.5px;">
            <span x-show="!isSaving">Save</span>
            <span x-show="isSaving">Saving...</span>
        </button>
        <button title="Close" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 rounded transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</header>
