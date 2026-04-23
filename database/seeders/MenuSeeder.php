<?php

namespace Acme\CmsDashboard\Database\Seeders;

use Illuminate\Database\Seeder;
use Acme\CmsDashboard\Models\Menu;
use Acme\CmsDashboard\Models\PostType;
use Acme\CmsDashboard\Models\CustomTaxonomy;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::truncate();

        // SVG Icons
        $icons = [
            'dashboard' => '<svg class="w-full h-full" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>',
            'posts' => '<svg class="w-full h-full" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.511 6h3.414l.459-2.29a1 1 0 111.961.394L14.885 6H17a1 1 0 110 2h-2.511l-.8 4H16a1 1 0 110 2h-2.711l-.459 2.29a1 1 0 11-1.961-.394L11.315 14H7.901l-.459 2.29a1 1 0 11-1.961-.394L5.94 14H4a1 1 0 110-2h2.34l.8-4H5a1 1 0 110-2h2.74l.459-2.29a1 1 0 011.044-.71zM12.525 8H9.111l-.8 4h3.414l.8-4z" clip-rule="evenodd" /></svg>',
            'media' => '<svg class="w-full h-full" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" /></svg>',
            'pages' => '<svg class="w-full h-full" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>',
            'comments' => '<svg class="w-full h-full" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" /></svg>',
            'menu' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>',
            'acpt' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
            'users' => '<svg class="w-full h-full" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a7 7 0 00-7 7v1h12v-1a7 7 0 00-7-7z" /></svg>',
            'settings' => '<svg class="w-full h-full" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>',
        ];

        // 1. Dashboard
        Menu::create([
            'title' => 'Dashboard',
            'route' => 'admin.dashboard.index',
            'icon'  => $icons['dashboard'],
            'group' => 'Main',
            'order' => 10,
        ]);

        // 2. Posts
        $postMenu = Menu::create([
            'title' => 'Posts',
            'route' => 'admin.posts.index',
            'icon'  => $icons['posts'],
            'group' => 'Main',
            'order' => 20,
        ]);
        $postMenu->children()->createMany([
            ['title' => 'All Posts',  'route' => 'admin.posts.index',      'order' => 1],
            ['title' => 'Add New',    'route' => 'admin.posts.create',     'order' => 2],
            ['title' => 'Categories', 'route' => 'admin.categories.index', 'order' => 3],
            ['title' => 'Tags',       'route' => 'admin.tags.index',       'order' => 4],
        ]);

        // 3. Media
        $mediaMenu = Menu::create([
            'title' => 'Media',
            'route' => 'admin.media.index',
            'icon'  => $icons['media'],
            'group' => 'Main',
            'order' => 30,
        ]);
        $mediaMenu->children()->createMany([
            ['title' => 'Library',        'route' => 'admin.media.index',  'order' => 1],
            ['title' => 'Add New',        'route' => 'admin.media.create', 'order' => 2],
        ]);

        // 4. Pages
        $pageMenu = Menu::create([
            'title' => 'Pages',
            'route' => 'admin.pages.index',
            'icon'  => $icons['pages'],
            'group' => 'Main',
            'order' => 25,
        ]);
        $pageMenu->children()->createMany([
            ['title' => 'All Pages', 'route' => 'admin.pages.index', 'order' => 1],
            ['title' => 'Add New',  'route' => 'admin.pages.create', 'order' => 2],
        ]);

        // 5. Comments
        Menu::create([
            'title' => 'Comments',
            'route' => '#',
            'icon'  => $icons['comments'],
            'group' => 'Main',
            'order' => 50,
        ]);

        // 6. Navigation
        Menu::create([
            'title' => 'Menu',
            'route' => 'admin.menus.index',
            'icon'  => $icons['menu'],
            'group' => 'Main',
            'order' => 60,
        ]);

        // 7. ACPT
        $acptMenu = Menu::create([
            'title' => 'ACPT',
            'route' => 'admin.acpt.cpt.index',
            'icon'  => $icons['acpt'],
            'group' => 'Advanced',
            'order' => 70,
        ]);
        $acptMenu->children()->createMany([
            ['title' => 'Post Types',   'route' => 'admin.acpt.cpt.index',        'order' => 1],
            ['title' => 'Taxonomies',   'route' => 'admin.acpt.taxonomies.index', 'order' => 2],
            ['title' => 'Field Groups', 'route' => 'admin.acpt.fields.index',      'order' => 3],
        ]);

        // 8. Users
        $userMenu = Menu::create([
            'title' => 'Users',
            'route' => 'admin.users.index',
            'icon'  => $icons['users'],
            'group' => 'System',
            'order' => 80,
        ]);
        $userMenu->children()->createMany([
            ['title' => 'All Users',    'route' => 'admin.users.index',     'order' => 1],
            ['title' => 'Add New',     'route' => 'admin.users.create',    'order' => 2],
            ['title' => 'Roles',       'route' => 'admin.roles.index',     'order' => 3],
            ['title' => 'Blacklist',   'route' => 'admin.blacklist.index', 'order' => 4],
            ['title' => 'Your Profile', 'route' => 'admin.profile',         'order' => 5],
        ]);

        // 9. Settings
        Menu::create([
            'title' => 'Settings',
            'route' => 'admin.settings.index',
            'icon'  => $icons['settings'],
            'group' => 'System',
            'order' => 90,
        ]);

        // 10. Fix DB (Super Admin Option)
        Menu::create([
            'title' => 'System Fix',
            'route' => '/admin/fix-db',
            'icon'  => 'build_circle',
            'group' => 'System',
            'order' => 100,
        ]);

        // Dynamic CPTs
        $customCPTs = PostType::where('is_builtin', false)->where('is_active', true)->get();
        foreach ($customCPTs as $cpt) {
            $cptParent = Menu::create([
                'title' => $cpt->name,
                'route' => url("/admin/posts?type={$cpt->slug}"),
                'icon'  => $cpt->icon ?: 'folder',
                'group' => 'Main',
                'order' => 40 + $cpt->id,
            ]);
            Menu::create(['parent_id' => $cptParent->id, 'title' => "All {$cpt->name}", 'route' => url("/admin/posts?type={$cpt->slug}"), 'order' => 1]);
            Menu::create(['parent_id' => $cptParent->id, 'title' => 'Add New', 'route' => url("/admin/posts/create?type={$cpt->slug}"), 'order' => 2]);
        }
    }
}
