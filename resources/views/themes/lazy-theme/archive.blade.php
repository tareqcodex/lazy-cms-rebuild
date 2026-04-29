@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', $title)

@section('content')
    <!-- Archive Header -->
    <section class="relative py-24 bg-white overflow-hidden">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-slate-50/50 -skew-x-12 transform translate-x-20"></div>
        <div class="container-custom relative">
            <div class="max-w-4xl mx-auto text-center">
                <span class="inline-block py-1.5 px-4 mb-6 text-[10px] font-black text-primary bg-blue-50 rounded-xl uppercase tracking-[0.2em]">
                    {{ $type === 'Search' ? 'Showing results for' : 'Browsing ' . $type }}
                </span>
                <h1 class="text-4xl lg:text-6xl font-black mb-8 leading-[1.1] tracking-tighter text-slate-900">
                    {{ $type === 'Search' ? '"' . request()->query('s') . '"' : $title }}
                </h1>
                <div class="h-1.5 w-16 bg-primary rounded-full mb-8 mx-auto"></div>
                <p class="text-lg text-slate-500 font-medium max-w-xl mx-auto">
                    @if($type === 'Search')
                        We've found {{ $posts->total() }} matching stories based on your search query.
                    @else
                        Discover all the stories and insights filed under the {{ strtolower($type) }} <strong>{{ $title }}</strong>.
                    @endif
                </p>
            </div>
        </div>
    </section>

    <!-- Content Area -->
    <section class="py-24 bg-[#fcfcfd] border-t border-slate-50">
        <div class="container-custom">
            @include('cms-dashboard::themes.lazy-theme.loop', ['posts' => $posts])
            
            <div class="mt-20">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
@endsection
