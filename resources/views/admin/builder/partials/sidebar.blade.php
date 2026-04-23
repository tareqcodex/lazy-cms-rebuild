<aside class="flex h-full bg-white border-r border-slate-200 shadow-sm" :style="`width:100%;`">

    <style>
        /* Toolbar/Sidebar Tooltips */
        .premium-btn { position: relative; }
        .setting-tooltip {
            position: absolute; bottom: 100%; left: 50%; transform: translate(-50%, -5px);
            background: #1e1e1e; color: #fff; font-size: 8px; font-weight: 800;
            padding: 5px 10px; border-radius: 4px; white-space: nowrap;
            opacity: 0; pointer-events: none; transition: all .2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 500; text-transform: uppercase; letter-spacing: 0.5px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .premium-btn:hover .setting-tooltip { opacity: 1; transform: translate(-50%, -10px); }
        .setting-tooltip::after {
            content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
            border: 4px solid transparent; border-top-color: #1e1e1e;
        }

        .premium-btn {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            color: #64748b;
        }
        .premium-btn:hover { background-color: #f1f5f9; border-color: #cbd5e1; color: #334155; }
        .premium-btn.active {
            background-color: #135E96;
            border-color: #135E96;
            color: #fff !important;
            box-shadow: 0 4px 6px -1px rgba(19, 94, 150, 0.2);
        }
        
        .icon-svg { width: 1.25rem; height: 1.25rem; stroke-width: 2.2; }

        /* Custom inputs */
        .premium-input {
            width: 100%; background: #fff; border: 1px solid #e2e8f0;
            padding: 10px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;
            color: #334155; outline: none; transition: all 0.2s;
        }
        .premium-input:focus { border-color: #135E96; box-shadow: 0 0 0 3px rgba(19, 94, 150, 0.1); }
        
        /* Design Tab Square Inputs */
        .square-input { border-radius: 0 !important; border-color: #e2e8f0; }
        .square-input:focus { border-color: #135E96; }
        .unit-select { 
            width: 45px; background: #f1f5f9; border: 1px solid #e2e8f0; border-left: 0;
            font-size: 9px; font-weight: 800; color: #64748b; outline: none; border-radius: 0;
            padding: 0 4px; appearance: none; text-align: center; cursor: pointer;
        }

        /* Navigator Specifics */
        .nav-item-row { display: flex; align-items: center; gap: 8px; padding: 7px 10px; border-radius: 6px; cursor: pointer; transition: all 0.2s; position: relative; }
        .nav-item-row:hover { background: #f8fafc; }
        .nav-item-row.active { background: #f0f9ff; color: #0369a1; }
        .nav-actions { display: flex; align-items: center; gap: 4px; margin-left: auto; opacity: 0; transition: opacity 0.2s; }
        .nav-item-row:hover .nav-actions { opacity: 1; }
        .nav-btn { padding: 4px; border-radius: 4px; color: #94a3b8; transition: all 0.2s; }
        .nav-btn:hover { background: #fff; color: #135E96; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .nav-btn.danger:hover { color: #ef4444; }

        /* Typography */
        .nav-label { font-size: 12px; font-weight: 700; letter-spacing: -0.01em; }
        .nav-sub-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; }
        
        /* Hide Arrows in number input */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>

    <!-- Sidebar strip -->
    <div class="flex flex-col items-center pt-3 gap-2 flex-shrink-0" style="width:36px; background:#333;">
        <button @@click="activeTab='settings'"
                :class="activeTab==='settings'?'text-white bg-[#135E96] shadow-md':'text-slate-400 hover:text-white'"
                class="w-6 h-6 flex items-center justify-center rounded transition-all" title="Settings">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </button>
        <button @@click="activeTab='navigator'"
                :class="activeTab==='navigator'?'text-white bg-[#135E96] shadow-md':'text-slate-400 hover:text-white'"
                class="w-6 h-6 flex items-center justify-center rounded transition-all" title="Navigator">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
        </button>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col flex-1 min-w-0 bg-[#f8fafc]" x-data="{ activeSubTab: 'general', unit: 'px' }">
        
        {{-- ── NAVIGATOR VIEW ── --}}
        <template x-if="activeTab==='navigator'">
            @include('cms-dashboard::admin.builder.partials.sidebar-navigator')
        </template>

        {{-- ── SETTINGS VIEW ── --}}
        <template x-if="activeTab==='settings'">
            <div class="flex flex-col h-full bg-[#f8fafc]">
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 bg-[#135E96] border-b border-[#135E96] shadow-md">
                    <div class="flex items-center gap-3">
                        <span class="font-black text-white text-[10px] uppercase tracking-[0.2em]" x-text="editingContext.type === 'container' ? 'Container Settings' : 'Column Settings'"></span>
                    </div>
                    <button @@click="activeTab='navigator'" class="text-white hover:bg-white/10 p-1.5 rounded-lg transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>

                <!-- Tabs -->
                <div class="flex bg-[#0f4a76] p-1 gap-1">
                    <button @@click="activeSubTab='general'" :class="activeSubTab==='general' ? 'bg-white/10 text-white' : 'text-white/40 hover:bg-white/5'" class="flex-1 py-1.5 flex items-center justify-center rounded font-black text-[9px] uppercase tracking-widest">General</button>
                    <button @@click="activeSubTab='design'" :class="activeSubTab==='design' ? 'bg-white/10 text-white' : 'text-white/40 hover:bg-white/5'" class="flex-1 py-1.5 flex items-center justify-center rounded font-black text-[9px] uppercase tracking-widest">Design</button>
                    <button @@click="activeSubTab='background'" :class="activeSubTab==='background' ? 'bg-white/10 text-white' : 'text-white/40 hover:bg-white/5'" class="flex-1 py-1.5 flex items-center justify-center rounded font-black text-[9px] uppercase tracking-widest">Background</button>
                    <button @@click="activeSubTab='extras'" :class="activeSubTab==='extras' ? 'bg-white/10 text-white' : 'text-white/40 hover:bg-white/5'" class="flex-1 py-1.5 flex items-center justify-center rounded font-black text-[9px] uppercase tracking-widest">Extras</button>
                </div>

                <!-- Control Panel -->
                <div class="flex-1 overflow-y-auto p-6 space-y-8 custom-scrollbar" x-show="editingContext.ci !== null && layout[editingContext.ci]">
                    {{-- @include('cms-dashboard::admin.builder.partials.sidebar-general') --}}
                    @include('cms-dashboard::admin.builder.partials.sidebar-general')
                    
                    {{-- @include('cms-dashboard::admin.builder.partials.sidebar-design') --}}
                    @include('cms-dashboard::admin.builder.partials.sidebar-design')

                    @include('cms-dashboard::admin.builder.partials.sidebar-background')
                    
                    @include('cms-dashboard::admin.builder.partials.sidebar-extras')
                </div>
            </div>
        </template>
    </div>
</aside>
