<?php

namespace Acme\CmsDashboard\Database\Seeders;

use Illuminate\Database\Seeder;
use Acme\CmsDashboard\Models\Role;
use Acme\CmsDashboard\Models\Permission;
use Illuminate\Support\Facades\DB;

class SystemSyncSeeder extends Seeder
{
    public function run()
    {
        // 1. Sync Permissions
        $permissions = [
            ['name' => 'Manage Content', 'slug' => 'manage_content'],
            ['name' => 'Manage Users', 'slug' => 'manage_users'],
            ['name' => 'Manage Roles', 'slug' => 'manage_roles'],
            ['name' => 'Manage Settings', 'slug' => 'manage_settings'],
            ['name' => 'Manage Media', 'slug' => 'manage_media'],
            ['name' => 'Manage Posts', 'slug' => 'manage_posts'],
            ['name' => 'Manage Pages', 'slug' => 'manage_pages'],
            ['name' => 'Manage Categories', 'slug' => 'manage_categories'],
            ['name' => 'Manage Tags', 'slug' => 'manage_tags'],
            // Add any new permissions here in future updates
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 2. Sync Roles & Link Permissions
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Unrestricted access to all system features.'],
            ['name' => 'Administrator', 'slug' => 'administrator', 'description' => 'Full access to all settings and content.'],
            ['name' => 'Editor', 'slug' => 'editor', 'description' => 'Can publish and manage posts.'],
            ['name' => 'Author', 'slug' => 'author', 'description' => 'Can publish and manage their own posts.'],
            ['name' => 'Subscriber', 'slug' => 'subscriber', 'description' => 'Can only manage their profile.'],
        ];

        $allPermissionIds = Permission::pluck('id')->toArray();

        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(['slug' => $roleData['slug']], $roleData);
            
            // Auto-link all permissions to Super Admin and Administrator
            if ($role->slug === 'super-admin' || $role->slug === 'administrator') {
                $role->permissions()->sync($allPermissionIds);
            }
        }

        // 3. Sync Default Menus (Run the existing MenuSeeder)
        $this->call(MenuSeeder::class);
    }
}
