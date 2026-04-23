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

        // 1. Dashboard
        Menu::create([
            'title' => 'Dashboard',
            'route' => 'admin.dashboard.index',
            'icon'  => 'dashboard',
            'group' => 'Main',
            'order' => 10,
        ]);

        // 2. Posts
        $postMenu = Menu::create([
            'title' => 'Posts',
            'route' => 'admin.posts.index',
            'icon'  => 'push_pin',
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
            'icon'  => 'perm_media',
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
            'icon'  => 'description',
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
            'icon'  => 'chat_bubble',
            'group' => 'Main',
            'order' => 50,
        ]);

        // 6. Navigation
        Menu::create([
            'title' => 'Menu',
            'route' => 'admin.menus.index',
            'icon'  => 'menu',
            'group' => 'Main',
            'order' => 60,
        ]);

        // 7. ACPT
        $acptMenu = Menu::create([
            'title' => 'ACPT',
            'route' => 'admin.acpt.cpt.index',
            'icon'  => 'settings_input_component',
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
            'icon'  => 'group',
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
            'icon'  => 'settings',
            'group' => 'System',
            'order' => 90,
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
