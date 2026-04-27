@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', $post->title)

@section('content')
    <!-- Article Header -->
    <section class="pt-20 pb-12 bg-white">
        <div class="page-container">
            <div class="mb-6">
                <span class="inline-block py-1 px-3 text-xs font-bold text-primary bg-blue-50 rounded-full uppercase tracking-widest">
                    {{ $post->type }}
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black mb-8 leading-tight tracking-tighter text-gray-900">
                {{ $post->title }}
            </h1>
            <div class="flex items-center gap-6 text-gray-400 text-sm border-b border-gray-100 pb-8">
                <div class="flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                    <span class="font-semibold text-gray-600">{{ $post->user->name ?? 'Admin' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    <div class="page-container -mt-8">
        <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/'.$post->featured_image) }}" 
             class="w-full h-[500px] object-cover rounded-[2.5rem] shadow-2xl border-8 border-white" 
             alt="{{ $post->title }}">
    </div>

    <!-- Content Area -->
    <article class="py-20 bg-white">
        <div class="page-container">
            <div class="prose prose-lg prose-blue max-w-none prose-headings:font-black prose-headings:tracking-tighter prose-p:leading-relaxed prose-p:text-gray-600">
                @if($post->editor_type === 'builder')
                    {!! get_lazy_content($post->content) !!}
                @else
                    {!! $post->content !!}
                @endif
            </div>

            <!-- Share and Tags -->
            <div class="mt-20 pt-10 border-t border-gray-100 flex flex-wrap items-center justify-between gap-6">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('frontend.tag', $tag->slug) }}" class="px-4 py-2 bg-gray-50 text-gray-500 rounded-full text-xs font-bold hover:bg-primary hover:text-white transition">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Comments Section -->
            @include('cms-dashboard::themes.lazy-theme.partials.comments')
        </div>
    </article>
@endsection
