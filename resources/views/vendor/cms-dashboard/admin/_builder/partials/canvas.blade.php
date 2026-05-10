<div class="h-full overflow-y-auto" style="background:#fff;">
    <div class="pt-[64px] pb-8 w-full mx-auto">

        <!-- Empty state -->
        <template x-if="layout.length===0">
            @include('cms-dashboard::admin.builder.partials.empty-state')
        </template>

        <!-- Rendered containers -->
        <div x-show="layout.length>0" style="display:flex; flex-direction:column; gap:32px;">
            <template x-for="(container, ci) in layout" :key="container.id">
                @include('cms-dashboard::admin.builder.partials.container-row')
            </template>

            <!-- Add container CTA -->
            <div class="flex justify-center py-12">
                <button @@click="openColumnModal()"
                    class="flex items-center gap-2 bg-white hover:bg-slate-50 text-[#2ea2cc] border-2 border-dashed border-[#2ea2cc]/40 hover:border-[#2ea2cc] px-7 py-3 rounded text-[11px] font-bold uppercase tracking-widest transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Add Container
                </button>
            </div>
        </div>
    </div>
</div>
