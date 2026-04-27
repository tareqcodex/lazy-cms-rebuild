@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', $title)

@section('content')
    <!-- Archive Header -->
    <section class="py-20 bg-white border-b border-gray-100">
        <div class="page-container">
            <div class="max-w-3xl">
                <span class="inline-block py-1 px-3 mb-4 text-xs font-bold text-primary bg-blue-50 rounded-full uppercase tracking-widest">
                    Archive
                </span>
                <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tighter text-gray-900">
                    {{ $title }}
                </h1>
                <p class="text-xl text-gray-400 leading-relaxed">
                    Explore all stories and updates matching the collection of <span class="text-primary font-bold">{{ $title }}</span>.
                </p>
            </div>
        </div>
    </section>

    <!-- Posts Grid -->
    <section class="py-20 lg:py-32 bg-gray-50">
        <div class="page-container">
            @include('cms-dashboard::themes.lazy-theme.loop', ['posts' => $items])

            <!-- Pagination -->
            @if($items->hasPages())
                <div class="mt-20 flex justify-center">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
