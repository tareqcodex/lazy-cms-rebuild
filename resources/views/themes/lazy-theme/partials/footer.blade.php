<footer class="bg-white border-t border-slate-100 pt-20 pb-10">
    <div class="container-custom">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <!-- Footer Column 1 -->
            <div class="col-span-1">
                @php $footer1 = render_lazy_widgets('footer-1'); @endphp
                @if($footer1)
                    {!! $footer1 !!}
                @else
                    <a href="{{ url('/') }}" class="flex items-center gap-2 mb-6">
                        @if(get_cms_option('theme_logo'))
                            <img src="{{ asset('storage/' . get_cms_option('theme_logo')) }}" alt="{{ get_cms_option('site_title', 'Lazy CMS') }}" class="h-8 w-auto">
                        @else
                            <span class="text-xl font-black tracking-tighter text-slate-900">
                                {{ get_cms_option('site_title', 'LAZY') }}<span class="text-primary">.</span>
                            </span>
                        @endif
                    </a>
                    <p class="text-slate-500 text-[14px] leading-relaxed mb-8">
                        {{ get_cms_option('footer_about', 'A minimalist, Astra-inspired theme for Lazy CMS. Clean, fast, and professional design focusing on readability and content delivery.') }}
                    </p>
                    @php
                        $footerSvgs = [
                            'fb' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
                            'tw' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg>',
                            'ins' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
                            'li' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>'
                        ];
                    @endphp
                    <div class="flex items-center gap-3">
                        @if($fb = get_cms_option('social_facebook'))
                        <a href="{{ $fb }}" target="_blank" class="w-9 h-9 rounded-lg flex items-center justify-center text-white transition-all hover:scale-110 shadow-sm" style="background-color: #1877F2;">
                            {!! $footerSvgs['fb'] !!}
                        </a>
                        @endif
                        @if($tw = get_cms_option('social_twitter'))
                        <a href="{{ $tw }}" target="_blank" class="w-9 h-9 rounded-lg flex items-center justify-center text-white transition-all hover:scale-110 shadow-sm" style="background-color: #000000;">
                            {!! $footerSvgs['tw'] !!}
                        </a>
                        @endif
                        @if($ins = get_cms_option('social_instagram'))
                        <a href="{{ $ins }}" target="_blank" class="w-9 h-9 rounded-lg flex items-center justify-center text-white transition-all hover:scale-110 shadow-sm" style="background-color: #E4405F;">
                            {!! $footerSvgs['ins'] !!}
                        </a>
                        @endif
                        @if($li = get_cms_option('social_linkedin'))
                        <a href="{{ $li }}" target="_blank" class="w-9 h-9 rounded-lg flex items-center justify-center text-white transition-all hover:scale-110 shadow-sm" style="background-color: #0077B5;">
                            {!! $footerSvgs['li'] !!}
                        </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Footer Column 2 -->
            <div class="col-span-1">
                @php $footer2 = render_lazy_widgets('footer-2'); @endphp
                @if($footer2)
                    {!! $footer2 !!}
                @else
                    <h4 class="text-slate-900 font-bold mb-6">Quick Links</h4>
                    <nav class="flex flex-col gap-3">
                        @php $footerMenu = get_lazy_menu('footer'); @endphp
                        @forelse($footerMenu as $item)
                            <a href="{{ $item->url }}" class="text-[14px] text-slate-500 hover:text-primary transition-colors">{{ $item->title }}</a>
                        @empty
                            <a href="{{ url('/') }}" class="text-[14px] text-slate-500 hover:text-primary transition-colors">Home</a>
                        @endforelse
                    </nav>
                @endif
            </div>

            <!-- Footer Column 3 -->
            <div class="col-span-1">
                @php $footer3 = render_lazy_widgets('footer-3'); @endphp
                @if($footer3)
                    {!! $footer3 !!}
                @else
                    <h4 class="text-slate-900 font-bold mb-6">Contact Us</h4>
                    <div class="space-y-4">
                        @if(get_cms_option('contact_email'))
                        <div class="flex items-start gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-primary mt-1"></i>
                            <span class="text-[14px] text-slate-500">{{ get_cms_option('contact_email') }}</span>
                        </div>
                        @endif
                        @if(get_cms_option('contact_address'))
                        <div class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-primary mt-1"></i>
                            <span class="text-[14px] text-slate-500">{{ get_cms_option('contact_address') }}</span>
                        </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Footer Column 4 -->
            <div class="col-span-1">
                @php $footer4 = render_lazy_widgets('footer-4'); @endphp
                @if($footer4)
                    {!! $footer4 !!}
                @else
                    <h4 class="text-slate-900 font-bold mb-6">Newsletter</h4>
                    <p class="text-[13px] text-slate-500 mb-4">Subscribe to get latest updates and news.</p>
                    <div class="relative">
                        <input type="email" placeholder="Your email..." class="w-full bg-slate-50 border-none rounded px-4 py-2.5 text-sm focus:ring-1 focus:ring-primary">
                        <button class="absolute right-1 top-1 bottom-1 px-3 bg-primary text-white rounded text-xs font-bold">JOIN</button>
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-8 border-t border-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[13px] text-slate-400">{{ get_cms_option('footer_copyright', '© ' . date('Y') . ' Lazy Panda. All rights reserved.') }}</p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-[12px] text-slate-400 hover:text-primary transition-colors">Privacy Policy</a>
                <a href="#" class="text-[12px] text-slate-400 hover:text-primary transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
