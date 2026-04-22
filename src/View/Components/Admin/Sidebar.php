<?php

namespace Acme\CmsDashboard\View\Components\Admin;

use Acme\CmsDashboard\Models\Menu;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Sidebar extends Component
{
    public $menuGroups;
    public $activeMenu; // e.g. 'posts', 'pages', 'movies', 'dashboard'

    public function __construct(?string $activeMenu = null)
    {
        $this->activeMenu = $activeMenu ?? $this->detectActiveMenu();

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

    protected function detectActiveMenu(): string
    {
        $segments = request()->segments();
        $type = request()->query('type');

        // Dashboard: Exactly /admin
        if (count($segments) === 1 && $segments[0] === 'admin') return 'dashboard';

        $module = $segments[1] ?? '';

        // Pages module (admin/pages/*)
        if ($module === 'pages') return 'pages';

        // Posts/Content module (admin/posts/*)
        if ($module === 'posts') {
            if (!$type || $type === 'post') return 'posts';
            if ($type === 'page') return 'pages'; // Normalize page type to pages menu
            return $type; // e.g. 'movies', etc.
        }

        return $module;
    }

    public function resolveRoute($routeStr, $title = '')
    {
        if (!$routeStr || $routeStr === '#') {
            // Fallback for core Post items if seeder wasn't run
            if ($title === 'Categories') return route('admin.categories.index');
            if ($title === 'Tags') return route('admin.tags.index');
            if ($title === 'All Posts') return route('admin.posts.index');
            if ($title === 'Add Post') return route('admin.posts.create');
            if ($title === 'All Pages') return route('admin.pages.index');
            if ($title === 'Add New' || $title === 'Add Page') return route('admin.pages.create');

            // Fallback for dynamic CPTs if they have no route set yet
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
