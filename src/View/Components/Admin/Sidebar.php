<?php

namespace Acme\CmsDashboard\View\Components\Admin;

use Acme\CmsDashboard\Models\Menu;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Sidebar extends Component
{
    public $menuGroups;
    public $activeMenu;

    public function __construct(?string $activeMenu = null)
    {
        $this->activeMenu = $activeMenu;

        try {
            $this->menuGroups = Menu::with('children')
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get()
                ->groupBy('group');
        } catch (\Exception $e) {
            $this->menuGroups = collect();
        }
    }

    public static function isUrlActive($url)
    {
        if (!$url || $url === '#') return false;

        $targetUrl = parse_url($url);
        $targetPath = trim($targetUrl['path'] ?? '', '/');
        $currentPath = trim(request()->getPathInfo(), '/');

        // 1. Dashboard: Must be exact match 'admin'
        if ($targetPath === 'admin') {
            return $currentPath === 'admin';
        }

        // 2. Base path check: Current path must start with target path
        if (!str_starts_with($currentPath, $targetPath)) {
            return false;
        }

        // 3. Query Parameter Strict Check (Crucial for CPTs and Taxonomies)
        parse_str($targetUrl['query'] ?? '', $targetQuery);
        
        $currentType = request()->query('type');
        $currentCpt = request()->query('cpt_slug') ?? request()->query('cpt');

        // Fallback: Detect type from route if on edit/create page
        if (!$currentType) {
            $route = request()->route();
            if ($route) {
                $post = $route->parameter('post');
                if ($post instanceof \Acme\CmsDashboard\Models\Post) {
                    $currentType = $post->type;
                } elseif (is_numeric($post)) {
                    $currentType = \Acme\CmsDashboard\Models\Post::where('id', $post)->value('type');
                } else {
                    $currentType = $route->parameter('type');
                }
            }
        }

        $targetType = $targetQuery['type'] ?? $targetQuery['cpt_slug'] ?? $targetQuery['cpt'] ?? null;

        // If target has a type/cpt_slug, current request MUST match it
        if ($targetType) {
            return ($currentType === $targetType || $currentCpt === $targetType);
        }

        // If target has NO type, current request should also have NO type (for standard Posts/Pages)
        // Except if we are on a standard sub-page like admin/categories
        if (!$targetType && ($currentType || $currentCpt)) {
            // If the current type is 'post' or 'page', it's still considered a "standard" type 
            // if the targetPath matches admin/posts or admin/pages
            if (($targetPath === 'admin/posts' && $currentType === 'post') || 
                ($targetPath === 'admin/pages' && $currentType === 'page')) {
                return true;
            }

            if ($targetPath === 'admin/posts' || $targetPath === 'admin/pages') {
                return false;
            }
        }

        return true;
    }

    public static function canAccess($url)
    {
        if (!$url || $url === '#') return true;
        if (!auth()->check()) return false;
        $user = auth()->user();

        $targetUrl = parse_url($url);
        $targetPath = trim($targetUrl['path'] ?? '', '/');
        parse_str($targetUrl['query'] ?? '', $targetQuery);

        $type = $targetQuery['type'] ?? $targetQuery['cpt_slug'] ?? null;

        // Dashboard
        if ($targetPath === 'admin') return true;

        // Content / Posts / Pages
        if (str_contains($targetPath, 'admin/posts') || str_contains($targetPath, 'admin/pages')) {
            $pType = $type ?: (str_contains($targetPath, 'admin/pages') ? 'page' : 'post');
            $permission = ($pType === 'page') ? 'manage_pages' : (($pType === 'post') ? 'manage_posts' : 'manage_' . $pType);
            return $user->hasPermission($permission);
        }

        // Users
        if (str_contains($targetPath, 'admin/users') || str_contains($targetPath, 'admin/blacklist')) return $user->hasPermission('manage_users');
        
        // Roles
        if (str_contains($targetPath, 'admin/roles')) return $user->hasPermission('manage_roles');

        // Settings
        if (str_contains($targetPath, 'admin/settings') || $targetPath === 'admin/dashboard/settings') return $user->hasPermission('manage_settings');

        // Media
        if (str_contains($targetPath, 'admin/media')) return $user->hasPermission('manage_media');

        // ACPT
        if (str_contains($targetPath, 'admin/acpt')) return $user->hasPermission('manage_settings');

        return true;
    }

    public function resolveRoute($routeStr, $title = '')
    {
        if (!$routeStr || $routeStr === '#') {
            if ($title === 'Categories') return route('admin.categories.index');
            if ($title === 'Tags') return route('admin.tags.index');
            if ($title === 'All Posts') return route('admin.posts.index');
            if ($title === 'Add Post') return route('admin.posts.create');
            if ($title === 'All Pages') return route('admin.pages.index');
            if ($title === 'Add New' || $title === 'Add Page') return route('admin.pages.create');

            try {
                $postType = \Acme\CmsDashboard\Models\PostType::where('name', $title)->first();
                if ($postType) {
                    return url('/admin/posts?type=' . $postType->slug);
                }
            } catch (\Exception $e) {}

            return '#';
        }

        if (str_starts_with($routeStr, '/') || str_starts_with($routeStr, 'http')) return url($routeStr);
        return Route::has($routeStr) ? route($routeStr) : $routeStr;
    }

    public function render()
    {
        return view('cms-dashboard::components.admin.sidebar');
    }
}
