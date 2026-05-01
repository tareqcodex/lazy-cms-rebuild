@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', $post->title)

@section('content')
    @php
        $isFullWidth = ($post->template ?? 'site-width') === 'full-width';
    @endphp

    <!-- Page Header -->
    <section class="py-20 bg-slate-50 border-b border-slate-100">
        <div class="page-container text-center">
            <h1 class="text-4xl lg:text-6xl font-bold mb-6 tracking-tight text-slate-900">
                {{ $post->title }}
            </h1>
            <div class="flex justify-center">
                @include('cms-dashboard::components.frontend.breadcrumbs', ['post' => $post])
            </div>
        </div>
    </section>

    <!-- Page Content -->
    <section class="py-20 bg-white">
        <div class="page-container">
            @if(!empty($post->featured_image) && !$isFullWidth)
                <div class="mb-16 max-w-5xl mx-auto">
                    <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/'.$post->featured_image) }}" 
                         class="w-full h-auto rounded shadow-sm border border-slate-100" 
                         alt="{{ $post->title }}">
                </div>
            @endif

            <div class="{{ $isFullWidth ? '' : 'max-w-4xl mx-auto' }}">
                <div class="prose prose-xl prose-slate max-w-none 
                    prose-headings:font-bold prose-headings:text-slate-900
                    prose-p:text-slate-600 prose-p:leading-relaxed
                    prose-img:rounded">
                    @if($post->editor_type === 'builder')
                        {!! get_lazy_content($post->content) !!}
                    @else
                        {!! do_lazy_shortcode($post->content) !!}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
