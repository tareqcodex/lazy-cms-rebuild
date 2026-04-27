<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('get_cms_option')) {
    function get_cms_option($key, $default = null)
    {
        try {
            $value = DB::table('cms_settings')->where('key', $key)->value('value');
            return $value !== null ? $value : $default;
        } catch (\Exception $e) {
            return $default;
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
            return view('cms-dashboard::frontend.builder.render', ['layout' => $layout])->render();
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
        ];
        $args = array_merge($defaults, $args);
        $query = \Acme\CmsDashboard\Models\Post::where('type', $args['post_type']);
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
        return \Acme\CmsDashboard\Models\Post::where('slug', $slugOrId)->first();
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

        $menu = $query->first();
        if (!$menu) return collect();

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
                // Clean URL
                if (!in_array($item->type, ['category', 'custom']) && $item->object_id) {
                    $post = \Acme\CmsDashboard\Models\Post::find($item->object_id);
                    if ($post) {
                        $item->url = get_lazy_permalink($post);
                    }
                } elseif ($item->type === 'page' && !empty($item->url)) {
                    if (str_starts_with($item->url, '/page/')) {
                        $item->url = '/' . substr($item->url, 6);
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
        if ($post->type === 'page') {
            return url($post->slug);
        }
        return route('frontend.show', ['typeOrSlug' => $post->type, 'slug' => $post->slug]);
    }
}