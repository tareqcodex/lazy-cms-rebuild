<header class="glass-nav sticky top-0 z-50">
    <div class="site-container">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ url('/') }}" class="text-2xl font-black tracking-tighter text-primary">
                    {{ strtoupper(get_cms_option('site_title', 'LAZY')) }}<span class="text-gray-900">THEME</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex space-x-8 items-center">
                @foreach(get_lazy_menu('header') as $item)
                    @php
                        $isCurrent = (url()->current() == $item->url);
                        $hasActiveChild = $item->children->filter(function($child) {
                            return url()->current() == $child->url || $child->children->filter(fn($c) => url()->current() == $c->url)->count() > 0;
                        })->count() > 0;
                        $isActive = $isCurrent || $hasActiveChild;
                    @endphp
                    <div class="relative group">
                        <a href="{{ $item->url }}" class="text-sm font-semibold transition flex items-center gap-1 {{ $isActive ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                            {{ $item->title }}
                            @if($item->children->count() > 0)
                                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform group-hover:rotate-180"></i>
                            @endif
                            @if($isActive)
                                <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                            @endif
                        </a>
                        
                        @if($item->children->count() > 0)
                            <div class="absolute top-full left-0 mt-2 w-56 bg-white shadow-xl rounded-xl border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50">
                                @foreach($item->children as $child)
                                    @php 
                                        $isChildCurrent = (url()->current() == $child->url);
                                        $hasActiveGrandChild = $child->children->filter(fn($c) => url()->current() == $c->url)->count() > 0;
                                        $isChildActive = $isChildCurrent || $hasActiveGrandChild;
                                    @endphp
                                    <div class="relative group/sub">
                                        <a href="{{ $child->url }}" class="flex items-center justify-between px-4 py-2.5 text-sm transition {{ $isChildActive ? 'bg-blue-50 text-primary font-bold' : 'text-gray-600 hover:bg-blue-50 hover:text-primary' }}">
                                            <span>{{ $child->title }}</span>
                                            @if($child->children->count() > 0)
                                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                            @endif
                                        </a>
                                        
                                        @if($child->children->count() > 0)
                                            <div class="absolute top-0 left-full ml-0 w-56 bg-white shadow-xl rounded-xl border border-gray-100 py-2 opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all duration-300 transform translate-x-2 group-hover/sub:translate-x-0 z-50">
                                                @foreach($child->children as $grandchild)
                                                    @php $isGrandActive = (url()->current() == $grandchild->url); @endphp
                                                    <a href="{{ $grandchild->url }}" class="block px-4 py-2.5 text-sm transition {{ $isGrandActive ? 'bg-blue-50 text-primary font-bold' : 'text-gray-600 hover:bg-blue-50 hover:text-primary' }}">
                                                        {{ $grandchild->title }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                <form action="{{ route('frontend.search') }}" method="GET" class="relative group/search">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search" 
                           class="bg-[#f5f5f5] border-none text-black placeholder-gray-400 rounded-full px-5 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all w-48 lg:w-64 outline-none">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 group-hover/search:text-primary transition-colors">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>
            </nav>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button class="text-gray-900 focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>
</header>
