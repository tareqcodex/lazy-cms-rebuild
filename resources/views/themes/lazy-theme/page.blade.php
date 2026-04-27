@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', $post->title)

@section('content')
    @php
        $isBuilder = ($post->editor_type === 'builder');
        $isFullWidth = ($post->template === 'full-width');
    @endphp

    @if(!$isBuilder || !$isFullWidth)
        <!-- Page Header (Only show if not a full-width builder page, or customize as needed) -->
        <section class="py-20 bg-gray-50">
            <div class="page-container text-center">
                <h1 class="text-4xl md:text-6xl font-black mb-4 tracking-tighter text-gray-900">
                    {{ $post->title }}
                </h1>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto leading-relaxed">
                    Home / {{ $post->title }}
                </p>
            </div>
        </section>
    @endif

    <!-- Content Area -->
    <section class="{{ ($isBuilder && $isFullWidth) ? 'py-0' : 'py-20' }} bg-white">
        @if($isBuilder && $isFullWidth)
            {{-- For Full Width Builder pages, we don't wrap in theme-container or card --}}
            <div class="lazy-builder-content">
                {!! get_lazy_content($post->content) !!}
            </div>
        @else
            <div class="page-container">
                <div class="bg-white rounded-[2rem] p-8 lg:p-16 shadow-2xl shadow-gray-200/50 border border-gray-100 min-h-[400px]">
                    <div class="prose prose-lg prose-blue max-w-none prose-headings:font-black prose-p:text-gray-600">
                        @if($isBuilder)
                            {!! get_lazy_content($post->content) !!}
                        @else
                            {!! $post->content !!}
                        @endif
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="max-w-3xl mx-auto">
                    @include('cms-dashboard::themes.lazy-theme.partials.comments')
                </div>
            </div>
        @endif
    </section>
@endsection
