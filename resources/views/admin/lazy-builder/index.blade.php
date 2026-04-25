<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lazy Builder | {{ $post->title }}</title>
    
    <!-- Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind (Corrected Paths) -->
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <script>
        // Fallback to CDN if local fails
        if (typeof tailwind === 'undefined') {
            document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        }
    </script>

    @include('cms-dashboard::admin.lazy-builder.partials.styles')
</head>
<body class="bg-[#f1f1f1]">

    <div id="lazy-builder-app" class="builder-wrapper" :class="{ 'is-preview': isPreview }" v-cloak>
        
        <!-- Topbar -->
        <header class="builder-topbar">
            @include('cms-dashboard::admin.lazy-builder.partials.topbar_content')
        </header>

        <!-- Sidebar -->
        <template v-if="!isPreview">
            @include('cms-dashboard::admin.lazy-builder.partials.sidebar')
        </template>

        <!-- Canvas -->
        @include('cms-dashboard::admin.lazy-builder.partials.canvas')

        <!-- Modals -->
        @include('cms-dashboard::admin.lazy-builder.partials.modals.column-select')
        @include('cms-dashboard::admin.lazy-builder.partials.modals.element-select')
    </div>

    @include('cms-dashboard::components.admin.media-modal')

    <!-- Scripts (Corrected Paths) -->
    <script src="{{ asset('vendor/cms-dashboard/js/vue.global.js') }}"></script>
    <script>
        // Fallback to CDN if local fails
        if (typeof Vue === 'undefined') {
            document.write('<script src="https://unpkg.com/vue@3/dist/vue.global.js"><\/script>');
        }
    </script>
    
    @include('cms-dashboard::admin.lazy-builder.partials.scripts')
</body>
</html>
