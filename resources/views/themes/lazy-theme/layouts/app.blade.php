<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $seo_meta = isset($post) && is_array($post->seo_meta) ? $post->seo_meta : [];
    @endphp
    <title>@yield('title', get_cms_option('site_title', 'Lazy CMS'))</title>
    @if(!empty($seo_meta['title']))
        <meta name="title" content="{{ $seo_meta['title'] }}">
    @endif
    <meta name="description" content="@yield('meta_description', !empty($seo_meta['description']) ? $seo_meta['description'] : get_cms_option('site_description', ''))">
    @if(!empty($seo_meta['keywords']))
        <meta name="keywords" content="{{ $seo_meta['keywords'] }}">
    @endif
    @if(!empty($seo_meta['og_image']))
        <meta property="og:image" content="{{ asset('storage/' . $seo_meta['og_image']) }}">
        <meta property="og:title" content="{{ !empty($seo_meta['title']) ? $seo_meta['title'] : (isset($post) ? $post->title : get_cms_option('site_title')) }}">
    @endif
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e293b',
                    }
                }
            }
        }
    </script>

    @php
        $siteWidth = '1460px';
        $pageContentWidth = $siteWidth;
        if (isset($post)) {
            if ($post->type === 'page') {
                $template = $post->template ?? 'site-width';
                if ($template === 'full-width') {
                    $pageContentWidth = '100%';
                }
            }
        }
    @endphp

    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        body {
            background-color: #f8fafc;
        }
        .site-container {
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            max-width: {{ $siteWidth }};
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .page-container {
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            max-width: {{ $pageContentWidth }};
            padding-left: 1rem;
            padding-right: 1rem;
        }
    </style>
    @yield('styles')
</head>
<body class="font-sans antialiased text-gray-900">

    @include('cms-dashboard::themes.lazy-theme.partials.header')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('cms-dashboard::themes.lazy-theme.partials.footer')

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      lucide.createIcons();
    </script>
    @yield('scripts')
</body>
</html>
