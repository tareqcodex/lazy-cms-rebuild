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

        // 2. Base path check
        $indexPaths = ['admin/posts', 'admin/pages', 'admin/users', 'admin/settings', 'admin/roles', 'admin/categories', 'admin/tags', 'admin/comments', 'admin/profile'];
        
        // Special case: Your Profile belongs to Users group
        if ($targetPath === 'admin/users' && ($currentPath === 'admin/profile' || str_starts_with($currentPath, 'admin/users/') && str_ends_with($currentPath, '/edit'))) {
            // If we are editing the CURRENT user, then Your Profile should be active
            $route = request()->route();
            if ($route && $route->getName() === 'admin.users.edit') {
                $userParam = $route->parameter('user');
                $userId = ($userParam instanceof \App\Models\User) ? $userParam->id : $userParam;
                if ((int)$userId === (int)auth()->id()) {
                    return false; // Parent itself not active, but children loop will find it
                }
            }
        }

        // Special case for Your Profile child item
        if ($targetPath === 'admin/profile') {
            if ($currentPath === 'admin/profile') return true;
            
            $route = request()->route();
            if ($route && $route->getName() === 'admin.users.edit') {
                $userParam = $route->parameter('user');
                $userId = ($userParam instanceof \App\Models\User) ? $userParam->id : $userParam;
                return (int)$userId === (int)auth()->id();
            }
        }
        
        if (in_array($targetPath, $indexPaths)) {
            // Index routes MUST be an exact match (ignoring query strings for now) 
            // unless it's a specific type (handled in step 3)
            if ($currentPath !== $targetPath) {
                // If it's something like admin/posts/create, the index 'admin/posts' should be inactive
                return false;
            }
        } elseif (!str_starts_with($currentPath, $targetPath)) {
            return false;
        }

        // 3. Query Parameter & Type Strict Check
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
                    try {
                        $currentType = \Acme\CmsDashboard\Models\Post::where('id', $post)->value('type');
                    } catch (\Exception $e) {}
                } else {
                    $currentType = $route->parameter('type');
                }
            }
        }

        $targetType = $targetQuery['type'] ?? $targetQuery['cpt_slug'] ?? $targetQuery['cpt'] ?? null;

        // If target has a type/cpt_slug, current request MUST match it
        if ($targetType) {
            if ($currentType !== $targetType && $currentCpt !== $targetType) {
                return false;
            }
            
            // Even if types match, if target is an index path, current path MUST match exactly
            if (in_array($targetPath, $indexPaths) && $currentPath !== $targetPath) {
                return false;
            }

            return true;
        }

        // IMPORTANT: If target belongs to standard Posts/Pages but HAS NO TYPE (it's a root or general menu),
        // it should NOT be active if the CURRENT request has a custom type (CPT).
        if (!$targetType && !empty($currentType) && !in_array($currentType, ['post', 'page'])) {
            $isTargetPosts = str_starts_with($targetPath, 'admin/posts');
            $isTargetPages = str_starts_with($targetPath, 'admin/pages');

            if ($isTargetPosts || $isTargetPages) {
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
        
        // Comments
        if (str_contains($targetPath, 'admin/comments')) return $user->hasPermission('manage_posts');

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
            if ($title === 'Comments') return route('admin.comments.index');

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
