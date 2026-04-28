<footer class="bg-white border-t border-slate-100 pt-20 pb-10">
    <div class="container-custom">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <!-- Brand & About -->
            <div class="col-span-1 lg:col-span-1">
                <a href="{{ url('/') }}" class="flex items-center gap-2 mb-6">
                    <span class="text-xl font-black tracking-tighter text-slate-900">
                        LAZY<span class="text-primary">.</span>
                    </span>
                </a>
                <p class="text-slate-500 text-[14px] leading-relaxed mb-8">
                    A minimalist, Astra-inspired theme for Lazy CMS. Clean, fast, and professional design focusing on readability and content delivery.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all">
                        <i data-lucide="facebook" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all">
                        <i data-lucide="twitter" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all">
                        <i data-lucide="instagram" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <div class="col-span-1">
                <h4 class="text-slate-900 font-bold mb-6">Quick Links</h4>
                <nav class="flex flex-col gap-3">
                    @php $footerMenu = get_lazy_menu('footer'); @endphp
                    @forelse($footerMenu as $item)
                        <a href="{{ $item->url }}" class="text-[14px] text-slate-500 hover:text-primary transition-colors">{{ $item->title }}</a>
                    @empty
                        <a href="{{ url('/') }}" class="text-[14px] text-slate-500 hover:text-primary transition-colors">Home</a>
                    @endforelse
                </nav>
            </div>

            <!-- Contact Info -->
            <div class="col-span-1">
                <h4 class="text-slate-900 font-bold mb-6">Contact Us</h4>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <i data-lucide="mail" class="w-4 h-4 text-primary mt-1"></i>
                        <span class="text-[14px] text-slate-500">hello@lazypanda.com</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-4 h-4 text-primary mt-1"></i>
                        <span class="text-[14px] text-slate-500">123 CMS Street, Web City, WP 101</span>
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="col-span-1">
                <h4 class="text-slate-900 font-bold mb-6">Newsletter</h4>
                <p class="text-[13px] text-slate-500 mb-4">Subscribe to get latest updates and news.</p>
                <div class="relative">
                    <input type="email" placeholder="Your email..." class="w-full bg-slate-50 border-none rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-primary">
                    <button class="absolute right-1 top-1 bottom-1 px-3 bg-primary text-white rounded text-xs font-bold">JOIN</button>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[13px] text-slate-400">© 2024 Lazy Panda. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-[12px] text-slate-400 hover:text-primary transition-colors">Privacy Policy</a>
                <a href="#" class="text-[12px] text-slate-400 hover:text-primary transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
