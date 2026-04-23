<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - Lazy CMS</title>
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <link href="{{ asset('vendor/cms-dashboard/css/inter.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white border-b border-gray-200 py-4 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-extrabold text-[#2271b1]">Lazy <span class="text-gray-800">CMS</span></a>
        </div>
    </header>

    <main>
        @if($post->editor_type !== 'builder')
            <article class="max-w-4xl mx-auto px-4 py-12">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-8">{{ $post->title }}</h1>
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! $post->content !!}
                </div>
            </article>
        @else
            <x-cms-dashboard::post-renderer :post="$post" />
        @endif
    </main>

    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400">© {{ date('Y') }} Proudly powered by Lazy CMS</p>
        </div>
    </footer>
</body>
</html>
