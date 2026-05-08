<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', get_cms_option('site_title', 'Lazy CMS'))</title>
    
    @php
        // Prepare Theme Options
        $primaryColor = get_cms_option('theme_primary_color', '#0091ea');
        $secondaryColor = get_cms_option('theme_secondary_color', '#1d2327');
        $bodyBgColor = get_cms_option('theme_body_bg_color', '#ffffff');
        $textColor = get_cms_option('theme_text_color', '#1d2327');
        $linkColor = get_cms_option('theme_link_color', '#0091ea');
        $linkHoverColor = get_cms_option('theme_link_hover_color', '#007ac1');
        $headingColor = get_cms_option('theme_heading_color', '#1d2327');
        $siteWidth = get_cms_option('theme_site_width', '1240px');
        $favicon = get_cms_option('theme_site_favicon');
        
        // Typography Processing
        $bodyTypo = json_decode(get_cms_option('theme_typography_body'), true) ?: ['family' => 'Inter', 'variant' => '400', 'size' => '15px'];
        $h1Typo = json_decode(get_cms_option('theme_typography_h1'), true);
        $navTypo = json_decode(get_cms_option('theme_typography_nav'), true);
        
        // Collect fonts to load
        $fontsToLoad = [$bodyTypo['family'] ?? 'Inter'];
        if (isset($h1Typo['family'])) $fontsToLoad[] = $h1Typo['family'];
        if (isset($navTypo['family'])) $fontsToLoad[] = $navTypo['family'];
        $fontsToLoad = array_unique($fontsToLoad);
        $googleFontsUrl = "https://fonts.googleapis.com/css2?family=" . implode('&family=', array_map(fn($f) => str_replace(' ', '+', $f) . ':wght@300;400;500;600;700;800;900', $fontsToLoad)) . "&display=swap";

        // Layout Type
        $layoutType = get_cms_option('theme_layout_type', 'wide');
    @endphp

    <!-- Favicon -->
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    @endif

    <!-- Meta SEO -->
    @yield('seo')

    <!-- Dynamic Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $googleFontsUrl }}" rel="stylesheet">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $primaryColor }}',
                        'primary-hover': '{{ $linkHoverColor }}',
                    },
                    fontFamily: {
                        sans: ['{{ $bodyTypo["family"] }}', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --primary: {{ $primaryColor }};
            --primary-hover: {{ $linkHoverColor }};
            --text-main: {{ $textColor }};
            --text-heading: {{ $headingColor }};
            --text-muted: #666666;
            --bg-body: {{ $bodyBgColor }};
            --bg-alt: #f5f7f9;
            --border-color: #e8e8e8;
            --site-width: {{ is_numeric($siteWidth) ? $siteWidth . 'px' : $siteWidth }};
            
            /* Typography Variables */
            --body-font: '{{ $bodyTypo["family"] }}', sans-serif;
            --body-size: {{ $bodyTypo["size"] ?? '15px' }};
            --body-lh: {{ $bodyTypo["line_height"] ?? '1.6' }};
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: var(--body-font);
            font-size: var(--body-size);
            line-height: var(--body-lh);
            
            @if(get_cms_option('theme_body_bg_image'))
                background-image: url('{{ get_cms_option("theme_body_bg_image") }}');
                background-position: {{ get_cms_option("theme_body_bg_position", "center center") }};
                background-size: {{ get_cms_option("theme_body_bg_size", "cover") }};
                background-repeat: {{ get_cms_option("theme_body_bg_repeat", "no-repeat") }};
                background-attachment: {{ get_cms_option("theme_body_bg_attachment", "scroll") }};
            @endif
        }

        @if($layoutType === 'boxed')
        body {
            background-color: #f0f0f1; /* Darker bg for boxed layout contrast */
        }
        .boxed-wrapper {
            max-width: var(--site-width);
            margin: 0 auto;
            background: var(--bg-body);
            box-shadow: 0 0 50px rgba(0,0,0,0.1);
        }
        @endif

        a { color: {{ $linkColor }}; transition: color 0.2s; }
        a:hover { color: {{ $linkHoverColor }}; }

        /* Typography Tags */
        @php
            $tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'nav'];
            foreach($tags as $tag) {
                $optionName = "theme_typography_{$tag}";
                $typo = json_decode(get_cms_option($optionName), true);
                if($typo) {
                    echo "{$tag}, .{$tag}-style { ";
                    if(isset($typo['family'])) echo "font-family: '{$typo['family']}', sans-serif; ";
                    if(isset($typo['size'])) echo "font-size: {$typo['size']}; ";
                    if(isset($typo['variant'])) echo "font-weight: {$typo['variant']}; ";
                    if(isset($typo['line_height'])) echo "line-height: {$typo['line_height']}; ";
                    if(isset($typo['letter_spacing'])) echo "letter-spacing: {$typo['letter_spacing']}; ";
                    if(isset($typo['text_transform'])) echo "text-transform: {$typo['text_transform']}; ";
                    if(isset($typo['text_decoration'])) echo "text-decoration: {$typo['text_decoration']}; ";
                    if(isset($typo['font_style'])) echo "font-style: {$typo['font_style']}; ";
                    echo "color: var(--text-heading); }\n";
                }
            }
        @endphp

        /* Astra-style Header Customization */
        .main-header {
            background: {{ get_cms_option('theme_header_bg_color', '#ffffff') }};
            color: {{ get_cms_option('theme_header_text_color', '#1d2327') }};
            height: {{ get_cms_option('theme_header_height', '80px') }};
            padding-top: {{ get_cms_option('theme_header_padding_top', '0px') }};
            padding-bottom: {{ get_cms_option('theme_header_padding_bottom', '0px') }};
            @if(get_cms_option('theme_header_border_bottom', '1') == '1')
                border-bottom: 1px solid {{ get_cms_option('theme_header_border_color', '#e8e8e8') }};
            @else
                border-bottom: none;
            @endif
        }

        @if(get_cms_option('theme_header_sticky', '0') == '1')
        .main-header {
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        @endif

        /* Container & Padding */
        .container-custom, .page-container {
            max-width: var(--site-width) !important;
            padding-left: {{ get_cms_option('theme_100_width_padding', '20px') }};
            padding-right: {{ get_cms_option('theme_100_width_padding', '20px') }};
        }

        main {
            padding-top: {{ get_cms_option('theme_page_padding_top', '60px') }};
            padding-bottom: {{ get_cms_option('theme_page_padding_bottom', '60px') }};
        }

        /* PRIORITY CUSTOM CSS */
        {!! get_cms_option('theme_custom_css') !!}
    </style>
    
    @yield('styles')
    {!! do_lazy_action('lazy_head') !!}

    {{-- PRIORITY HEAD SCRIPT --}}
    @if(get_cms_option('theme_head_script'))
        <script>{!! get_cms_option('theme_head_script') !!}</script>
    @endif
</head>
@php
    $defaultTemplate = get_cms_option('theme_default_template', 'site-width');
    $bodyClasses = "antialiased selection:bg-primary selection:text-white";
    if ($layoutType !== 'boxed') {
        $bodyClasses .= " template-{$defaultTemplate}";
    }
@endphp
<body class="{{ $bodyClasses }}">

    @if($layoutType === 'boxed') <div class="boxed-wrapper min-h-screen flex flex-col"> @endif

    @include('cms-dashboard::themes.lazy-theme.partials.header')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('cms-dashboard::themes.lazy-theme.partials.footer')

    @if($layoutType === 'boxed') </div> @endif

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
    {!! do_lazy_action('lazy_footer') !!}

    {{-- PRIORITY FOOTER SCRIPT --}}
    @if(get_cms_option('theme_footer_script'))
        <script>{!! get_cms_option('theme_footer_script') !!}</script>
    @endif
</body>
</html>
