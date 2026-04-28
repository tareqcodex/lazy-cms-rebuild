<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', get_cms_option('site_title', 'Lazy CMS'))</title>
    
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
        .widget-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            font-size: 1.2rem;
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

        .container-custom {
            max-width: @yield('content-width', '1200px');
            margin-left: auto;
            margin-right: auto;
            padding-left: 20px;
            padding-right: 20px;
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
    </style>
    @yield('styles')
    {!! do_lazy_action('lazy_head') !!}
</head>
<body class="antialiased selection:bg-primary selection:text-white">

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
