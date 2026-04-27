<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Documentation - Lazy CMS</x-slot>

    <div class="px-6 py-4">
        <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900">Developer Documentation</h1>
                <p class="text-gray-500 mt-1">Master Lazy CMS and build stunning websites with freedom.</p>
            </div>
            <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold border border-blue-100">
                v1.1.2 Stable
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Navigation Sidebar --}}
            <div class="lg:col-span-1">
                <nav class="sticky top-6 space-y-1" id="doc-nav">
                    <a href="#getting-started" class="nav-link block px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md transition-all duration-200">Getting Started</a>
                    <a href="#custom-routes" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Custom Routes</a>
                    <a href="#helpers" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Helper Functions</a>
                    <a href="#loops" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Displaying Posts (Loops)</a>
                    <a href="#custom-options" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Custom Settings & Options</a>
                    <a href="#templates" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Custom Templates</a>
                </nav>
            </div>

            {{-- Content --}}
            <div class="lg:col-span-3 space-y-12 pb-20">
                
                {{-- Section: Getting Started --}}
                <section id="getting-started">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Getting Started</h2>
                    <p class="text-gray-700 mb-4">Lazy CMS is designed to give you full control over your content while keeping the development process simple. You can manage everything from the dashboard and display it anywhere using our global helpers.</p>
                </section>

                {{-- Section: Custom Routes --}}
                <section id="custom-routes">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Custom Routes</h2>
                    <p class="text-gray-700 mb-4">You can define your own routes in <code>routes/web.php</code>. These will take precedence over the CMS catch-all routes.</p>
                    <div class="bg-gray-900 rounded-xl p-6 text-gray-300 font-mono text-sm overflow-x-auto">
                        <pre><code>// Example: Custom Route for Blogs
Route::get('/blogs', function () {
    return view('blogs');
});</code></pre>
                    </div>
                </section>

                {{-- Section: Helpers --}}
                <section id="helpers">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Global Helper Functions</h2>
                    <div class="space-y-6">
                        {{-- get_lazy_posts --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_lazy_posts($args)</h3>
                            <p class="text-sm text-gray-600 mb-2">Fetch posts with advanced options like pagination and filtering.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm mb-2">$posts = get_lazy_posts(['post_type' => 'post', 'limit' => 10, 'paginate' => true]);</code>
                        </div>

                        {{-- the_lazy_pagination --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">the_lazy_pagination($items, $view)</h3>
                            <p class="text-sm text-gray-600 mb-4">Render pagination links with custom design support.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs">
                                <pre><code>@verbatim{!! the_lazy_pagination($postItems) !!}
{!! the_lazy_pagination($postItems, 'custom-view') !!}@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- get_lazy_post --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_lazy_post($slugOrId)</h3>
                            <p class="text-sm text-gray-600 mb-2">Get a single post/page data by its slug or ID.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm">$post = get_lazy_post('about-us');</code>
                        </div>

                        {{-- get_cms_option --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_cms_option($key, $default)</h3>
                            <p class="text-sm text-gray-600 mb-2">Get any setting value from the CMS settings table.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm">$site_name = get_cms_option('site_title', 'Lazy CMS');</code>
                        </div>

                        {{-- get_lazy_excerpt --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_lazy_excerpt($post, $limit)</h3>
                            <p class="text-sm text-gray-600 mb-2">Extracts plain text from builder JSON or rich text content.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm">$excerpt = get_lazy_excerpt($post, 150);</code>
                        </div>

                        {{-- get_lazy_categories --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_lazy_categories($taxonomy)</h3>
                            <p class="text-sm text-gray-600 mb-2">Fetch all categories or custom taxonomy terms.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm">$cats = get_lazy_categories('category');</code>
                        </div>
                    </div>
                </section>

                {{-- Section: Archives --}}
                <section id="archives">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Archive & Filtering</h2>
                    <p class="text-gray-700 mb-4">CMS automatically handles archive pages for categories and tags.</p>
                    <div class="bg-gray-100 p-4 rounded-lg space-y-2 text-sm font-mono text-gray-700">
                        <div><span class="text-blue-600 font-bold">Category URL:</span> /category/{slug}</div>
                        <div><span class="text-blue-600 font-bold">Tag URL:</span> /tag/{slug}</div>
                    </div>
                </section>

                {{-- Section: Loops --}}
                <section id="loops">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Displaying Posts (Loops)</h2>
                    <p class="text-gray-700 mb-4">Use <code>the_lazy_loop()</code> for a quick grid, or write your own <code>@@foreach</code> for total freedom.</p>
                    
                    <h3 class="font-bold mt-6 mb-2">Method 1: Fast Grid</h3>
                    <div class="bg-gray-900 rounded-xl p-6 text-gray-300 font-mono text-sm mb-6">
                        <pre><code>the_lazy_loop(['post_type' => 'post', 'limit' => 6]);</code></pre>
                    </div>

                    <h3 class="font-bold mb-2">Method 2: Custom HTML with Pagination (Recommended)</h3>
                    <div class="bg-gray-900 rounded-xl p-6 text-gray-300 font-mono text-sm">
                        <pre><code>@verbatim@php $items = get_lazy_posts(['post_type' => 'post', 'limit' => 6, 'paginate' => true]); @endphp

@foreach($items as $post)
    <div class="card">
        <h2>{{ $post->title }}</h2>
        <a href="{{ route('frontend.category', $post->categories->first()->slug) }}">
            {{ $post->categories->first()->name }}
        </a>
    </div>
@endforeach

<div class="pagination">
    {!! the_lazy_pagination($items) !!}
</div>@endverbatim</code></pre>
                    </div>
                </section>

                {{-- Section: Custom Settings & Options Pages --}}
                <section id="custom-options">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Custom Settings & Options Pages</h2>
                    <p class="text-gray-700 mb-6">You can extend the CMS by adding new fields to existing settings or creating entirely new admin pages via <code>config/lazy-options.php</code>.</p>

                    <div class="space-y-8">
                        {{-- Adding to Main Settings --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">1. Add Fields to Main Settings</h3>
                            <p class="text-sm text-gray-600 mb-4">To add new inputs to the <b>General Settings</b> page, use the <code>hooks</code> array in your config.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs overflow-x-auto">
                                <pre><code>@verbatim// config/lazy-options.php
'hooks' => [
    'general-settings' => [
        'fields' => [
            'site_tagline' => [
                'type' => 'text',
                'label' => 'Site Tagline',
                'placeholder' => 'Enter slogan...',
            ],
        ]
    ]
]@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- Creating New Pages --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">2. Create a Custom Admin Page</h3>
                            <p class="text-sm text-gray-600 mb-4">You can create standalone pages that appear in the sidebar using the <code>pages</code> array.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs overflow-x-auto">
                                <pre><code>@verbatim// config/lazy-options.php
'pages' => [
    'theme-settings' => [
        'title' => 'Theme Options',
        'icon'  => 'palette',
        'group' => 'Appearance',
        'fields' => [
            'primary_color' => [
                'type' => 'text',
                'label' => 'Primary Brand Color',
                'default' => '#007bff'
            ],
            'footer_text' => [
                'type' => 'textarea',
                'label' => 'Footer Copyright Text',
            ],
        ]
    ]
]@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- Displaying Values --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">3. How to Show Values in Frontend</h3>
                            <p class="text-sm text-gray-600 mb-4">Values are automatically saved to the database. Use <code>get_cms_option()</code> to retrieve them anywhere.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs space-y-4">
                                <div>
                                    <span class="text-gray-500">// Get simple text value</span><br>
                                    <code class="text-blue-400">@verbatim{{ get_cms_option('site_tagline') }}@endverbatim</code>
                                </div>
                                <div>
                                    <span class="text-gray-500">// Get image URL</span><br>
                                    <code class="text-blue-400">@verbatim<img src="{{ asset(get_cms_option('header_logo')) }}">@endverbatim</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Section: Templates --}}
                <section id="templates">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Custom Templates</h2>
                    <p class="text-gray-700 mb-4">If you create a file in <code>resources/views/</code> that matches the <b>slug</b> of a CMS page, it will automatically be used to render that page.</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Page Slug: <code>contact-us</code></li>
                        <li>Blade File: <code>resources/views/contact-us.blade.php</code></li>
                    </ul>
                </section>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('section[id]');

            // 1. Smooth Scroll on Click
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('href');
                    document.querySelector(id).scrollIntoView({
                        behavior: 'smooth'
                    });
                    
                    // Update URL hash without jumping
                    history.pushState(null, null, id);
                });
            });

            // 2. Intersection Observer for Scrollspy
            const options = {
                rootMargin: '-20% 0px -70% 0px',
                threshold: 0
            };

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = '#' + entry.target.getAttribute('id');
                        
                        navLinks.forEach(link => {
                            link.classList.remove('text-blue-600', 'bg-blue-50');
                            link.classList.add('text-gray-600');

                            if (link.getAttribute('href') === id) {
                                link.classList.add('text-blue-600', 'bg-blue-50');
                                link.classList.remove('text-gray-600');
                            }
                        });
                    }
                });
            }, options);

            sections.forEach(section => observer.observe(section));
        });
    </script>
</x-cms-dashboard::layouts.admin>
