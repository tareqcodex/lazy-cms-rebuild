<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', get_cms_option('site_title', 'Lazy CMS'))</title>
    
    <!-- Favicon -->
    @if(get_cms_option('theme_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . get_cms_option('theme_favicon')) }}">
    @endif

    <!-- Meta SEO -->
    @yield('seo')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0274be',
                        'primary-hover': '#015a94',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --primary: #0274be;
            --primary-hover: #015a94;
            --text-main: #3a3a3a;
            --text-heading: #191919;
            --text-muted: #666666;
            --bg-body: #ffffff;
            --bg-alt: #f5f7f9;
            --border-color: #e8e8e8;
            @php 
                $siteWidth = get_cms_option('theme_site_width', '1200px');
                if (is_numeric($siteWidth)) $siteWidth .= 'px';
            @endphp
            --site-width: {{ $siteWidth }};
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
        }

        /* Astra-style Header */
        .main-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-heading);
            font-weight: 700;
            line-height: 1.3;
        }

        /* Card Simplification */
        .post-card {
            background: #fff;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        .post-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        /* Sidebar Widgets */
        .widget {
            margin-bottom: 2rem;
        }
        .widget-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .widget-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--primary);
        }

        /* Force Outer Container to be 100% for Backgrounds */
        .lazy-container {
            width: 100% !important;
            max-width: none !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .main-header,
        .main-footer,
        section.w-full {
            width: 100% !important;
            max-width: none !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 !important;
        }

        .container-custom,
        .page-container {
            width: 100%;
            max-width: var(--site-width) !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Site Width Template Logic - Inner Content Only */
        .template-site-width .container-custom,
        .template-site-width .lazy-container-inner:not(.w-full) {
            max-width: var(--site-width) !important;
            width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* Ensure w-full containers actually fill the space without padding */
        .lazy-container-inner.w-full {
            width: 100% !important;
            max-width: none !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 !important;
        }

        /* Allow builder sections to break out of page-container if needed */
        .template-site-width .prose {
            max-width: none !important;
            width: 100% !important;
        }

        .btn-premium {
            display: inline-block;
            padding: 12px 32px;
            background-color: var(--primary);
            color: white;
            font-weight: 700;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .btn-premium:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(2, 116, 190, 0.2);
        }

        /* Device Visibility Logic (User Breakpoints) */
        @media (min-width: 320px) and (max-width: 640px) {
            .lazy-hide-mobile { display: none !important; }
        }
        @media (min-width: 641px) and (max-width: 1024px) {
            .lazy-hide-tablet { display: none !important; }
        }
        @media (min-width: 1025px) {
            .lazy-hide-desktop { display: none !important; }
        }
        .lazy-hide-all { display: none !important; }

        /* Auto Responsive Columns for Mobile */
        @media (max-width: 767px) {
            .lazy-column {
                flex-basis: 100% !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }

        /* Hover Effects */
        .lazy-column, .lazy-container {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-effect-zoom:hover { transform: scale(1.03) !important; z-index: 50 !important; }
        .hover-effect-lift:hover { transform: translateY(-10px) !important; z-index: 50 !important; box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important; }
        .hover-effect-glow:hover { box-shadow: 0 0 25px rgba(0, 145, 234, 0.5) !important; z-index: 50 !important; }
        .hover-effect-fade:hover { opacity: 0.7 !important; }
    </style>
    @yield('styles')
    {!! do_lazy_action('lazy_head') !!}
</head>
<body class="antialiased selection:bg-primary selection:text-white template-site-width">

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
    @stack('scripts')
    {!! do_lazy_action('lazy_footer') !!}
</body>
</html>
