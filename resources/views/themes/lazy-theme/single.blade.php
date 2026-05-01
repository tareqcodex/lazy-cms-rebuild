@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', $post->title)

@section('content')
    <!-- Main Content Area -->
    <div class="py-16 bg-white">
        <div class="container-custom">
            <div class="flex flex-col lg:flex-row gap-16">
                
                <!-- Content Column -->
                <article class="w-full lg:w-[70%]">
                    <header class="mb-10">
                        <div class="mb-6">
                            @include('cms-dashboard::components.frontend.breadcrumbs', ['post' => $post])
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-slate-900 leading-tight">
                            {{ $post->title }}
                        </h1>
                        <div class="flex items-center gap-6 text-sm text-slate-400 border-b border-slate-100 pb-8">
                            <span class="flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                                <span class="font-bold text-slate-600">{{ $post->user->name ?? 'Admin' }}</span>
                            </span>
                            <span class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                            </span>
                        </div>
                    </header>

                    @if(!empty($post->featured_image))
                        <div class="mb-12">
                            <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/'.$post->featured_image) }}" 
                                 class="w-full h-auto rounded shadow-sm border border-slate-100" 
                                 alt="{{ $post->title }}"
                                 loading="lazy">
                        </div>
                    @endif

                    <div class="prose prose-lg prose-slate max-w-none 
                        prose-headings:text-slate-900 prose-headings:font-bold
                        prose-p:text-slate-600 prose-p:leading-relaxed
                        prose-a:text-primary hover:prose-a:underline
                        prose-img:rounded shadow-none">
                        @php 
                            $rawContent = ($post->editor_type === 'builder') ? get_lazy_content($post->content) : do_lazy_shortcode($post->content);
                            $filteredContent = apply_lazy_filters('lazy_the_content', $rawContent, $post);
                        @endphp

                        {!! do_lazy_action('lazy_before_content', $post) !!}
                        {!! $filteredContent !!}
                        {!! do_lazy_action('lazy_after_content', $post) !!}
                    </div>

                    <!-- Post Tags -->
                    @if($post->tags->isNotEmpty())
                        <div class="mt-16 pt-8 border-t border-slate-100">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tagged:</span>
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('frontend.tag', $tag->slug) }}" class="px-3 py-1 bg-slate-50 text-slate-600 rounded text-xs font-bold hover:bg-primary hover:text-white transition">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Comments -->
                    <div class="mt-20">
                        @include('cms-dashboard::themes.lazy-theme.partials.comments')
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="w-full lg:w-[30%] space-y-12">
                    @php $sidebarContent = render_lazy_widgets('primary-sidebar'); @endphp
                    @if($sidebarContent)
                        {!! $sidebarContent !!}
                    @else
                        <!-- Default Widgets if none configured -->
                        <div class="widget">
                            <h4 class="widget-title">Search</h4>
                            <form action="{{ route('frontend.search') }}" method="GET" class="relative">
                                <input type="text" name="q" placeholder="Type and hit enter..." class="w-full border border-slate-200 rounded px-4 py-3 text-sm focus:border-primary outline-none transition-all">
                            </form>
                        </div>

                        <div class="widget">
                            <h4 class="widget-title">Recent Posts</h4>
                            <div class="space-y-6">
                                @foreach(get_lazy_posts(['limit' => 5]) as $recent)
                                    <div class="flex gap-4 group">
                                        @if($recent->featured_image)
                                            <div class="w-16 h-16 shrink-0 bg-slate-50 rounded overflow-hidden border border-slate-100">
                                                <img src="{{ str_starts_with($recent->featured_image, 'http') ? $recent->featured_image : asset('storage/'.$recent->featured_image) }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $recent->title }}">
                                            </div>
                                        @endif
                                        <div>
                                            <h5 class="text-sm font-bold leading-snug group-hover:text-primary transition-colors">
                                                <a href="{{ get_lazy_permalink($recent) }}">{{ $recent->title }}</a>
                                            </h5>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-2 tracking-widest">{{ $recent->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>

            </div>
        </div>
    </div>
@endsection
