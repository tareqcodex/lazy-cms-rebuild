<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('get_cms_option')) {
    function get_cms_option($key, $default = null)
    {
        try {
            $currentLocale = app()->getLocale();
            $localeKey = $key . '_' . $currentLocale;
            
            // 1. Check for locale specific key first (e.g. site_title_bn)
            $value = DB::table('cms_settings')->where('key', $localeKey)->value('value');
            if ($value !== null) return $value;

            // 2. Fallback to default key
            $value = DB::table('cms_settings')->where('key', $key)->value('value');
            return $value !== null ? $value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('update_cms_option')) {
    function update_cms_option($key, $value)
    {
        try {
            \Illuminate\Support\Facades\DB::table('cms_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('get_custom_field')) {
    function get_custom_field($post, $fieldName, $default = null)
    {
        try {
            $postId = is_object($post) ? $post->id : $post;
            $value = DB::table('post_custom_field_values')
                ->join('custom_fields', 'post_custom_field_values.field_id', '=', 'custom_fields.id')
                ->where('post_custom_field_values.post_id', $postId)
                ->where('custom_fields.name', $fieldName)
                ->value('post_custom_field_values.value');
            return $value !== null ? $value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('get_lazy_content')) {
    function get_lazy_content($content)
    {
        if (empty($content)) return '';
        try {
            $layout = is_string($content) ? json_decode($content, true) : $content;
            if (!is_array($layout)) return '';
            $rendered = view('cms-dashboard::frontend.builder.render', ['layout' => $layout])->render();
            return do_lazy_shortcode($rendered);
        } catch (\Exception $e) {
            return '';
        }
    }
}

if (!function_exists('the_lazy_content')) {
    function the_lazy_content($content) { echo get_lazy_content($content); }
}

if (!function_exists('get_lazy_posts')) {
    function get_lazy_posts($args = []) {
        $defaults = [
            'post_type' => 'post',
            'limit' => 10,
            'order' => 'desc',
            'orderby' => 'created_at',
            'status' => 'published',
            'category' => null,
            'tag' => null,
            'paginate' => false,
            'lang' => null,
        ];
        $args = array_merge($defaults, $args);
        $query = \Acme\CmsDashboard\Models\Post::where('type', $args['post_type']);
        
        $lang = $args['lang'] ?: app()->getLocale();
        $query->where('lang_code', $lang);

        if ($args['status']) {
            $query->where('status', $args['status']);
        }
        if ($args['category']) {
            $query->whereHas('categories', function($q) use ($args) {
                $q->where('slug', $args['category']);
            });
        }
        if ($args['tag']) {
            $query->whereHas('tags', function($q) use ($args) {
                $q->where('slug', $args['tag']);
            });
        }
        $query->orderBy($args['orderby'], $args['order']);
        
        if ($args['paginate']) {
            return $query->paginate($args['limit']);
        }
        return $query->limit($args['limit'])->get();
    }
}

if (!function_exists('the_lazy_pagination')) {
    function the_lazy_pagination($items, $view = null) {
        if (!($items instanceof \Illuminate\Pagination\LengthAwarePaginator)) return '';
        return $items->links($view);
    }
}

if (!function_exists('the_lazy_loop')) {
    function the_lazy_loop($args = [], $view = 'cms-dashboard::frontend.loop')
    {
        $posts = get_lazy_posts($args);
        echo view($view, ['posts' => $posts])->render();
    }
}

if (!function_exists('get_lazy_excerpt')) {
    function get_lazy_excerpt($post, $limit = 120)
    {
        if ($post->editor_type !== 'builder') {
            return \Illuminate\Support\Str::limit(strip_tags($post->content), $limit);
        }
        try {
            $layout = is_string($post->content) ? json_decode($post->content, true) : $post->content;
            $text = '';
            if (is_array($layout)) {
                foreach ($layout as $container) {
                    if (!empty($container['columns'])) {
                        foreach ($container['columns'] as $column) {
                            if (!empty($column['elements'])) {
                                foreach ($column['elements'] as $el) {
                                    if ($el['type'] === 'heading') $text .= ($el['settings']['title'] ?? '') . ' ';
                                    elseif ($el['type'] === 'text') $text .= strip_tags($el['settings']['content'] ?? '') . ' ';
                                    if (strlen($text) > $limit) break 3;
                                }
                            }
                        }
                    }
                }
            }
            return \Illuminate\Support\Str::limit(trim($text), $limit);
        } catch (\Exception $e) { return ''; }
    }
}

if (!function_exists('get_lazy_post')) {
    function get_lazy_post($slugOrId) {
        if (is_numeric($slugOrId)) return \Acme\CmsDashboard\Models\Post::find($slugOrId);
        return \Acme\CmsDashboard\Models\Post::where('slug', $slugOrId)->where('lang_code', app()->getLocale())->first();
    }
}

if (!function_exists('get_lazy_categories')) {
    function get_lazy_categories($taxonomy = 'category') {
        if ($taxonomy === 'category') return \Acme\CmsDashboard\Models\Category::orderBy('name')->get();
        return \Acme\CmsDashboard\Models\TaxonomyTerm::where('taxonomy_slug', $taxonomy)->get();
    }
}

if (!function_exists('get_lazy_menu')) {
    function get_lazy_menu($slugOrLocation) {
        $query = \Acme\CmsDashboard\Models\NavigationMenu::query();
        
        if ($slugOrLocation === 'header') {
            $query->where('is_header', true);
        } elseif ($slugOrLocation === 'footer') {
            $query->where('is_footer', true);
        } else {
            $query->where('slug', $slugOrLocation);
        }

        $currentLocale = app()->getLocale();
        
        // Try to find menu with exact slug-locale if it's a slug
        if (!in_array($slugOrLocation, ['header', 'footer'])) {
            $langSlug = $slugOrLocation . '-' . $currentLocale;
            $menu = (clone $query)->where('slug', $langSlug)->first();
            if ($menu) return this_process_items($menu);
        }

        // Try to find by location AND lang_code
        $menu = (clone $query)->where('lang_code', $currentLocale)->first();
        
        if (!$menu) {
            // Fallback to location only without lang_code
            $menu = (clone $query)->whereNull('lang_code')->first();
        }

        if (!$menu) return collect();

        return this_process_items($menu);
    }
}

// Internal helper for menu processing (moved logic out of the main function for reuse)
if (!function_exists('this_process_items')) {
    function this_process_items($menu) {
        // Fetch active CPTs and Taxonomies to filter items
        $activePostTypes = \Acme\CmsDashboard\Models\PostType::where('is_active', true)->pluck('slug')->toArray();
        $activeTaxonomies = \Acme\CmsDashboard\Models\CustomTaxonomy::where('is_active', true)->pluck('slug')->toArray();

        // Built-in types are always active
        $activePostTypes[] = 'post';
        $activePostTypes[] = 'page';
        $activePostTypes[] = 'category'; // Default category
        $activePostTypes[] = 'custom';   // Custom links

        $items = $menu->items->filter(function($item) use ($activePostTypes, $activeTaxonomies) {
            // If it's a post/page/cpt item
            if (!in_array($item->type, ['category', 'custom'])) {
                return in_array($item->type, $activePostTypes);
            }
            // If it's a category/taxonomy item
            if ($item->type === 'category' && $item->object_id) {
                $term = \Acme\CmsDashboard\Models\TaxonomyTerm::find($item->object_id);
                if ($term) return in_array($term->taxonomy_slug, $activeTaxonomies);
                $standardCat = \Acme\CmsDashboard\Models\Category::find($item->object_id);
                return (bool) $standardCat;
            }
            return true;
        });

        $cleanItems = function($items) use (&$cleanItems) {
            return $items->map(function($item) use ($cleanItems) {
                $currentLocale = app()->getLocale();
                
                // If it's a post/page/cpt item, find translation
                if (!in_array($item->type, ['category', 'custom']) && $item->object_id) {
                    $post = \Acme\CmsDashboard\Models\Post::find($item->object_id);
                    if ($post) {
                        // Find translation in current locale
                        if ($post->lang_code !== $currentLocale) {
                            $translation = $post->getTranslation($currentLocale);
                            if ($translation) {
                                $post = $translation;
                            }
                        }
                        $item->url = get_lazy_permalink($post);
                    }
                }

                // Recursively clean children
                if ($item->children && $item->children->count() > 0) {
                    $item->setRelation('children', $cleanItems($item->children));
                }

                return $item;
            });
        };

        return $cleanItems($items);
    }
}

if (!function_exists('get_lazy_permalink')) {
    function get_lazy_permalink($post) {
        if (!$post) return '#';
        
        $postLang = $post->lang_code ?? 'en';
        $homePageId = get_cms_option('home_page_id');
        
        // Homepage usually doesn't need a slug, just the language prefix
        if ($post->id == $homePageId) {
            return ($postLang === 'en') ? url('/') : url('/' . $postLang);
        }

        $langPrefix = '/' . $postLang;

        if ($post->type === 'page') {
            return url($langPrefix . '/' . $post->slug);
        }
        return url($langPrefix . '/' . $post->type . '/' . $post->slug);
    }
}

if (!function_exists('clear_page_cache')) {
    function clear_page_cache() {
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('lazy_log_activity')) {
    function lazy_log_activity($action, $description, $model = null, $properties = []) {
        try {
            $ip = request()->ip();
            $country = null;
            $countryCode = null;

            // Simple IP to Country Cache/Lookup
            if ($ip && $ip !== '127.0.0.1' && $ip !== '::1') {
                try {
                    $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode");
                    if ($response) {
                        $data = json_decode($response, true);
                        if ($data && $data['status'] === 'success') {
                            $country = $data['country'];
                            $countryCode = $data['countryCode'];
                        }
                    }
                } catch (\Exception $e) {}
            }

            return \Acme\CmsDashboard\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $model ? $model->id : null,
                'description' => $description,
                'properties' => $properties,
                'ip_address' => $ip,
                'country' => $country,
                'country_code' => $countryCode,
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('render_lazy_widgets')) {
    function render_lazy_widgets($area) {
        $currentLocale = app()->getLocale();
        $query = \Acme\CmsDashboard\Models\Widget::forArea($area);
        
        // 1. Filter by lang_code
        $widgets = $query->where(function($q) use ($currentLocale) {
            $q->where('lang_code', $currentLocale)->orWhereNull('lang_code');
        })->get();

        $output = '';
        foreach ($widgets as $widget) {
            // 1. Try Theme Specific Widget first: themes/lazy-theme/widgets/name.blade.php
            $activeTheme = get_cms_option('active_theme', 'lazy-theme');
            $themeWidget = "cms-dashboard::themes.{$activeTheme}.widgets.{$widget->type}";
            
            // 2. Try Package Default Widget: frontend.widgets.name
            $defaultWidget = "cms-dashboard::frontend.widgets.{$widget->type}";

            if (view()->exists($themeWidget)) {
                $output .= view($themeWidget, ['widget' => $widget])->render();
            } elseif (view()->exists($defaultWidget)) {
                $output .= view($defaultWidget, ['widget' => $widget])->render();
            } else {
                // Fallback for custom HTML or simple text
                if ($widget->type === 'custom_html') {
                    $content = $widget->settings['content'] ?? '';
                    // Process Shortcodes if any system exists (placeholder for now)
                    $content = do_lazy_shortcode($content);

                    $output .= '<div class="widget mb-12">';
                    if ($widget->title) $output .= '<h4 class="widget-title">' . e($widget->title) . '</h4>';
                    $output .= $content;
                    $output .= '</div>';
                }
            }
        }
        return $output;
    }
}

// --- Hook System Helpers ---

if (!function_exists('add_lazy_action')) {
    function add_lazy_action($tag, $callback, $priority = 10) {
        \Acme\CmsDashboard\Core\HookManager::getInstance()->addAction($tag, $callback, $priority);
    }
}

if (!function_exists('do_lazy_action')) {
    function do_lazy_action($tag, ...$args) {
        \Acme\CmsDashboard\Core\HookManager::getInstance()->doAction($tag, ...$args);
    }
}

if (!function_exists('add_lazy_filter')) {
    function add_lazy_filter($tag, $callback, $priority = 10) {
        \Acme\CmsDashboard\Core\HookManager::getInstance()->addFilter($tag, $callback, $priority);
    }
}

if (!function_exists('apply_lazy_filters')) {
    function apply_lazy_filters($tag, $value, ...$args) {
        return \Acme\CmsDashboard\Core\HookManager::getInstance()->applyFilters($tag, $value, ...$args);
    }
}

if (!function_exists('remove_lazy_action')) {
    function remove_lazy_action($tag, $callback, $priority = 10) {
        return \Acme\CmsDashboard\Core\HookManager::getInstance()->removeAction($tag, $callback, $priority);
    }
}

if (!function_exists('remove_lazy_filter')) {
    function remove_lazy_filter($tag, $callback, $priority = 10) {
        return \Acme\CmsDashboard\Core\HookManager::getInstance()->removeFilter($tag, $callback, $priority);
    }
}

if (!function_exists('lazy_lang_switcher')) {
    function lazy_lang_switcher($showFlags = true) {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('cms_languages')) return '';
            $languages = \Acme\CmsDashboard\Models\Language::where('status', true)->get();
            if ($languages->count() <= 1) return '';
            
            $currentLocale = app()->getLocale();
            $output = '<div class="lazy-lang-switcher flex items-center space-x-3">';
            
            // Check if we are on a single post/page to find equivalents
            $currentPost = null;
            if (request()->route('typeOrSlug')) {
                $viewData = view()->getShared();
                if (isset($viewData['post'])) {
                    $currentPost = $viewData['post'];
                }
            }

            foreach ($languages as $lang) {
                $isActive = ($currentLocale == $lang->code);
                $url = url($lang->code); 
                
                if ($currentPost) {
                    $equivalent = $currentPost->getTranslation($lang->code);
                    if ($equivalent) {
                        $url = get_lazy_permalink($equivalent);
                    }
                }

                $output .= '<a href="' . $url . '" class="flex items-center text-[13px] ' . ($isActive ? 'font-bold text-blue-600' : 'text-gray-600 hover:text-black') . '">';
                if ($showFlags) $output .= '<span class="mr-1">' . $lang->flag . '</span> ';
                $output .= strtoupper($lang->code);
                $output .= '</a>';
            }
            $output .= '</div>';
            return $output;
        } catch (\Exception $e) {
            return '';
        }
    }
}

if (!function_exists('lazy_lang_dropdown')) {
    function lazy_lang_dropdown() {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('cms_languages')) return '';
            $activeLangs = \Acme\CmsDashboard\Models\Language::where('status', true)->get();
            if ($activeLangs->count() <= 1) return '';
            
            $currentLang = $activeLangs->where('code', app()->getLocale())->first() ?? $activeLangs->first();
            
            // Find current post to check for translations
            $currentPost = view()->getShared()['current_post'] ?? null;

            // Filter languages to only those that have a translation for the current post
            if ($currentPost) {
                $activeLangs = $activeLangs->filter(function($lang) use ($currentPost) {
                    if ($currentPost->lang_code == $lang->code) return true;
                    return (bool) $currentPost->getTranslation($lang->code);
                });
            }

            if ($activeLangs->count() <= 1) return '';

            $displayMode = get_cms_option('lang_switcher_display', 'both');
            
            $output = '<div class="relative group inline-block language-switcher-dropdown">';
            $output .= '<button class="flex items-center gap-1.5 text-slate-700 hover:text-primary transition-colors text-[13px] font-bold cursor-pointer" onclick="this.nextElementSibling.classList.toggle(\'hidden\')">';
            
            $currentLangCode = strtolower($currentLang->code);
            $countryMap = [
                'en' => 'us', 'bn' => 'bd', 'zh' => 'cn', 'ar' => 'sa', 'uk' => 'gb',
                'ja' => 'jp', 'ko' => 'kr', 'pt' => 'br', 'hi' => 'in', 'ru' => 'ru',
                'tr' => 'tr', 'it' => 'it', 'es' => 'es', 'fr' => 'fr', 'de' => 'de',
                'gb' => 'gb', 'cn' => 'cn', 'sa' => 'sa', 'kr' => 'kr', 'jp' => 'jp',
                'br' => 'br', 'in' => 'in'
            ];
            $currentFlagCode = $countryMap[$currentLangCode] ?? $currentLangCode;

            if (in_array($displayMode, ['both', 'flag_only'])) {
                $output .= '<span class="flex items-center justify-center w-5 h-4 overflow-hidden rounded-sm border border-slate-100 shadow-sm">';
                $output .= '<img src="' . url('/assets/flags/' . $currentFlagCode . '.png') . '" class="w-full h-full object-cover" alt="' . $currentLang->name . '">';
                $output .= '</span>';
            }
            
            if (in_array($displayMode, ['both', 'text_only'])) {
                $output .= '<span class="uppercase">' . $currentLang->name . '</span>';
            } elseif ($displayMode === 'code_only') {
                $output .= '<span class="uppercase">' . $currentLang->code . '</span>';
            }
            
            $output .= '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
            $output .= '</button>';
            $output .= '<div class="absolute top-full right-0 mt-2 w-32 bg-white border border-slate-100 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 rounded-md overflow-hidden">';
            $output .= '<ul class="py-1 m-0 list-none">';
            
            foreach($activeLangs as $lang) {
                $isActive = (app()->getLocale() == $lang->code);
                $url = route('frontend.set-locale', $lang->code);
                
                if ($currentPost) {
                    $equivalent = $currentPost->getTranslation($lang->code);
                    if ($equivalent) {
                        $url = get_lazy_permalink($equivalent);
                    } elseif ($currentPost->lang_code == $lang->code) {
                        $url = get_lazy_permalink($currentPost);
                    }
                }

                $output .= '<li>';
                $output .= '<a href="' . $url . '" class="flex items-center justify-between gap-2 px-4 py-2 text-[13px] font-medium text-slate-600 hover:text-primary hover:bg-slate-50 transition-all ' . ($isActive ? 'bg-slate-50 text-primary font-bold' : '') . '">';
                $output .= '<div class="flex items-center gap-2">';
                
                $langCode = strtolower($lang->code);
                $countryMap = [
                    'en' => 'us', 'bn' => 'bd', 'zh' => 'cn', 'ar' => 'sa', 'uk' => 'gb',
                    'ja' => 'jp', 'ko' => 'kr', 'pt' => 'br', 'hi' => 'in', 'ru' => 'ru',
                    'tr' => 'tr', 'it' => 'it', 'es' => 'es', 'fr' => 'fr', 'de' => 'de',
                    'gb' => 'gb', 'cn' => 'cn', 'sa' => 'sa', 'kr' => 'kr', 'jp' => 'jp',
                    'br' => 'br', 'in' => 'in'
                ];
                $flagCode = $countryMap[$langCode] ?? $langCode;

                if (in_array($displayMode, ['both', 'flag_only'])) {
                    $output .= '<span class="flex items-center justify-center w-5 h-4 overflow-hidden rounded-sm border border-slate-100 shadow-sm">';
                    $output .= '<img src="' . url('/assets/flags/' . $flagCode . '.png') . '" class="w-full h-full object-cover" alt="' . $lang->name . '">';
                    $output .= '</span>';
                }
                
                if (in_array($displayMode, ['both', 'text_only'])) {
                    $output .= '<span>' . $lang->name . '</span>';
                } elseif ($displayMode === 'code_only') {
                    $output .= '<span class="uppercase">' . $lang->code . '</span>';
                }
                
                $output .= '</div>';
                if ($isActive) {
                    $output .= '<svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                }
                $output .= '</a></li>';
            }
            
            $output .= '</ul></div></div>';
            return $output;
        } catch (\Exception $e) {
            return '';
        }
    }
}

if (!function_exists('lazy_mobile_lang_switcher')) {
    function lazy_mobile_lang_switcher() {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('cms_languages')) return '';
            $activeLangs = \Acme\CmsDashboard\Models\Language::where('status', true)->get();
            if ($activeLangs->count() <= 1) return '';
            
            // Find current post to check for translations
            $currentPost = view()->getShared()['current_post'] ?? null;

            // Filter languages to only those that have a translation for the current post
            if ($currentPost) {
                $activeLangs = $activeLangs->filter(function($lang) use ($currentPost) {
                    if ($currentPost->lang_code == $lang->code) return true;
                    return (bool) $currentPost->getTranslation($lang->code);
                });
            }

            if ($activeLangs->count() <= 1) return '';

            $displayMode = get_cms_option('lang_switcher_display', 'both');
            $output = '<div class="grid grid-cols-2 gap-2">';
            foreach($activeLangs as $lang) {
                $isActive = (app()->getLocale() == $lang->code);
                $url = route('frontend.set-locale', $lang->code);
                
                if ($currentPost) {
                    $equivalent = $currentPost->getTranslation($lang->code);
                    if ($equivalent) {
                        $url = get_lazy_permalink($equivalent);
                    } elseif ($currentPost->lang_code == $lang->code) {
                        $url = get_lazy_permalink($currentPost);
                    }
                }

                $output .= '<a href="' . $url . '" class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg border ' . ($isActive ? 'border-primary bg-primary/5 text-primary' : 'border-slate-100 text-slate-600') . ' transition-all">';
                $output .= '<div class="flex items-center gap-2">';
                
                $langCode = strtolower($lang->code);
                $countryMap = [
                    'en' => 'us', 'bn' => 'bd', 'zh' => 'cn', 'ar' => 'sa', 'uk' => 'gb',
                    'ja' => 'jp', 'ko' => 'kr', 'pt' => 'br', 'hi' => 'in', 'ru' => 'ru',
                    'tr' => 'tr', 'it' => 'it', 'es' => 'es', 'fr' => 'fr', 'de' => 'de',
                    'gb' => 'gb', 'cn' => 'cn', 'sa' => 'sa', 'kr' => 'kr', 'jp' => 'jp',
                    'br' => 'br', 'in' => 'in'
                ];
                $flagCode = $countryMap[$langCode] ?? $langCode;

                if (in_array($displayMode, ['both', 'flag_only'])) {
                    $output .= '<span class="w-6 h-4 overflow-hidden rounded-sm flex items-center justify-center shrink-0 border border-slate-100 shadow-sm">';
                    $output .= '<img src="' . url('/assets/flags/' . $flagCode . '.png') . '" class="w-full h-full object-cover" alt="' . $lang->name . '">';
                    $output .= '</span>';
                }
                
                if (in_array($displayMode, ['both', 'text_only'])) {
                    $output .= '<span class="text-[13px] font-semibold">' . $lang->name . '</span>';
                } elseif ($displayMode === 'code_only') {
                    $output .= '<span class="text-[13px] font-semibold uppercase">' . $lang->code . '</span>';
                }
                
                $output .= '</div>';
                if ($isActive) {
                    $output .= '<svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                }
                $output .= '</a>';
            }
            $output .= '</div>';
            return $output;
        } catch (\Exception $e) {
            return '';
        }
    }
}

if (!function_exists('the_lazy_lang_dropdown')) {
    function the_lazy_lang_dropdown() { echo lazy_lang_dropdown(); }
}

if (!function_exists('lazy_search_form')) {
    function lazy_search_form($placeholder = 'Search...') {
        $url = route('frontend.search');
        $output = '<form action="' . $url . '" method="GET" class="relative lazy-search-form">';
        $output .= '<input type="text" name="s" placeholder="' . e($placeholder) . '" class="w-full bg-slate-50 border border-slate-200 rounded-full px-5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">';
        $output .= '<button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-primary text-white rounded-full text-xs font-bold hover:bg-primary/90 transition-colors uppercase">Search</button>';
        $output .= '</form>';
        return $output;
    }
}

if (!function_exists('the_lazy_search_form')) {
    function the_lazy_search_form($placeholder = 'Search...') { echo lazy_search_form($placeholder); }
}

if (!function_exists('render_lazy_form')) {
    function render_lazy_form($slug) {
        try {
            $form = \Acme\CmsDashboard\Models\Form::where('slug', $slug)->where('status', true)->first();
            if (!$form || empty($form->fields)) return '';
            return view('cms-dashboard::frontend.form-renderer', ['form' => $form])->render();
        } catch (\Exception $e) {
            return '';
        }
    }
}

if (!function_exists('do_lazy_shortcode')) {
    function do_lazy_shortcode($content) {
        if (empty($content)) return $content;

        // Decode HTML entities so [lazy_form slug=&quot;x&quot;] becomes [lazy_form slug="x"]
        $decoded = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Handle [lazy_form slug="..."] or [lazy_form slug='...']
        $decoded = preg_replace_callback('/\[lazy_form\s+slug=["\']([^"\']+)["\']\s*\]/', function($matches) {
            return render_lazy_form($matches[1]);
        }, $decoded);

        $shortcodes = [
            '[lazy_search]'        => lazy_search_form(),
            '[lazy_lang_dropdown]' => lazy_lang_dropdown(),
        ];

        return str_replace(array_keys($shortcodes), array_values($shortcodes), $decoded);
    }
}

if (!function_exists('lazy_translate')) {
    function lazy_translate($text, $targetLang = 'en', $sourceLang = 'auto') {
        if (empty($text)) return $text;
        try {
            $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" . $sourceLang . "&tl=" . $targetLang . "&dt=t&q=" . urlencode($text);
            
            $options = [
                "http" => [
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
                ]
            ];
            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);
            
            if ($response) {
                $data = json_decode($response, true);
                $translated = '';
                if (isset($data[0])) {
                    foreach ($data[0] as $line) {
                        $translated .= $line[0];
                    }
                    return $translated;
                }
            }
        } catch (\Exception $e) {}
        return $text; 
    }
}