<!-- Empty state: matches Avada's "To get started" screen -->
<div class="bg-white border border-dashed border-slate-300 rounded">

    <!-- Top call-to-action section -->
    <div class="px-16 py-14 text-center border-b border-dashed border-slate-200">
        <h2 class="text-[22px] font-semibold text-slate-700 mb-2 leading-snug">
            To get started, add a Container.
        </h2>
        <p class="text-slate-400 text-[13px] mb-8">
            The building process always starts with a container, then columns, then elements.
        </p>
        <div class="flex items-center justify-center gap-3">
            <button @@click="showModal=true"
                class="flex items-center gap-2 bg-[#2ea2cc] hover:bg-[#2388ab] text-white px-6 py-2.5 rounded text-[12px] font-bold uppercase tracking-wider transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Add Container
            </button>
        </div>
    </div>

    <!-- Helper cards row -->
    <div class="grid grid-cols-2 divide-x divide-dashed divide-slate-200">
        <div class="px-10 py-10 text-center">
            <div class="w-14 h-14 rounded-full bg-slate-200 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-[13px] font-semibold text-slate-600 mb-1.5">Watch Our Get Started Video</h3>
            <p class="text-[11px] text-slate-400 mb-3">Do you need a helping hand? Let us guide you.</p>
            <a href="#" class="text-[11px] text-[#2ea2cc] font-semibold hover:underline">Watch The Video →</a>
        </div>
        <div class="px-10 py-10 text-center">
            <div class="w-14 h-14 rounded-full bg-slate-200 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h3 class="text-[13px] font-semibold text-slate-600 mb-1.5">Lazy Builder Docs</h3>
            <p class="text-[11px] text-slate-400 mb-3">Videos not for you? That's ok! We have you covered.</p>
            <a href="#" class="text-[11px] text-[#2ea2cc] font-semibold hover:underline">Builder Docs →</a>
        </div>
    </div>
</div>
