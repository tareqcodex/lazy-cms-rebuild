@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', 'Welcome to Lazy Theme - Home')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 lg:py-32 overflow-hidden bg-white">
        <div class="page-container">
            <div class="flex flex-wrap items-center -mx-4">
                <div class="w-full lg:w-1/2 px-4 mb-16 lg:mb-0">
                    <span class="inline-block py-1 px-3 mb-4 text-xs font-semibold text-primary bg-blue-50 rounded-full uppercase tracking-widest">
                        Ultimate CMS Experience
                    </span>
                    <h1 class="text-5xl lg:text-7xl font-extrabold mb-8 leading-tight tracking-tighter">
                        Build your dream site <br> <span class="text-primary italic">faster</span> than ever.
                    </h1>
                    <p class="text-xl text-gray-500 mb-10 max-w-lg leading-relaxed">
                        The modern content management system for creators, developers, and dreamers. Take full control of your digital world.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ url('/posts') }}" class="px-8 py-4 rounded-full bg-primary text-white font-bold shadow-2xl shadow-blue-500/50 hover:bg-blue-600 transition flex items-center gap-2">
                            Explore Blog <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </a>
                        <a href="#" class="px-8 py-4 rounded-full bg-white border border-gray-200 text-gray-900 font-bold hover:bg-gray-50 transition">
                            Documentation
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 px-4">
                    <div class="relative max-w-xl mx-auto lg:mx-0">
                        <img class="rounded-3xl shadow-2xl transform lg:rotate-3 hover:rotate-0 transition duration-500" src="https://picsum.photos/seed/lazy/800/600" alt="Hero Image">
                        <div class="absolute -bottom-10 -left-10 bg-white p-6 rounded-2xl shadow-xl hidden md:block">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    <i data-lucide="zap" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Lightning Fast</p>
                                    <p class="text-xs text-gray-500">Optimized Performance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Posts Section -->
    <section class="py-20 lg:py-32 bg-gray-50">
        <div class="page-container">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <h2 class="text-4xl font-black mb-4 tracking-tight">Recent Stories</h2>
                    <p class="text-gray-500 max-w-md">Stay updated with the latest news, updates and insights from our community.</p>
                </div>
                <a href="{{ url('/posts') }}" class="hidden md:flex items-center gap-2 font-bold text-primary hover:gap-3 transition-all">
                    View All Posts <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </a>
            </div>

            @include('cms-dashboard::themes.lazy-theme.loop', ['posts' => get_lazy_posts(['limit' => 3])])
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20">
        <div class="page-container">
            <div class="bg-primary rounded-[3rem] p-12 lg:p-20 text-center relative overflow-hidden shadow-2xl shadow-blue-500/40">
                <h2 class="text-4xl lg:text-6xl font-black text-white mb-8 tracking-tighter">
                    Ready to build your <br> next big idea?
                </h2>
                <p class="text-blue-100 text-lg mb-12 max-w-xl mx-auto leading-relaxed">
                    Join thousands of creators who use Lazy CMS to power their digital experiences every single day.
                </p>
                <a href="#" class="inline-block px-10 py-5 rounded-full bg-white text-primary font-bold text-lg hover:scale-105 transition transform active:scale-95 shadow-xl">
                    Get Started Now
                </a>
            </div>
        </div>
    </section>
@endsection
