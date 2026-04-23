<?php

namespace Acme\CmsDashboard\Console\Commands;

use Illuminate\Console\Command;
use Acme\CmsDashboard\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InstallLazyCms extends Command
{
    protected $signature = 'lazy-cms:install';
    protected $description = 'Install Lazy CMS, run migrations, and create a default super admin';

    public function handle()
    {
        $this->info('Installing Lazy CMS...');

        // 1. Run Migrations
        $this->info('Running migrations...');
        $this->call('migrate', ['--force' => true]);

        // 2. Publish Assets
        $this->info('Publishing assets...');
        $this->call('vendor:publish', [
            '--tag' => 'cms-dashboard-assets',
            '--force' => true
        ]);

        // 3. Set Default Options
        $this->info('Setting up default configurations...');
        $options = [
            'login_url' => 'lazy-admin',
            'register_url' => 'lazy-lazy-registration',
            'login_theme' => 'breeze',
            'register_theme' => 'breeze',
        ];

        foreach ($options as $key => $value) {
            DB::table('cms_settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        }

        // 4. Create Default Permissions
        $this->info('Creating default permissions...');
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
        ];

        foreach ($permissions as $p) {
            \Acme\CmsDashboard\Models\Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 5. Create Default Roles
        $this->info('Creating default roles...');
        $roles = [
            ['name' => 'Administrator', 'slug' => 'administrator', 'description' => 'Full access to all settings and content.'],
            ['name' => 'Editor', 'slug' => 'editor', 'description' => 'Can publish and manage posts.'],
            ['name' => 'Author', 'slug' => 'author', 'description' => 'Can publish and manage their own posts.'],
            ['name' => 'Subscriber', 'slug' => 'subscriber', 'description' => 'Can only manage their profile.'],
        ];

        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(['slug' => $roleData['slug']], $roleData);
            
            if ($role->slug === 'administrator') {
                $allPermissionIds = \Acme\CmsDashboard\Models\Permission::pluck('id')->toArray();
                $role->permissions()->sync($allPermissionIds);
            }
        }

        // 6. Run Menu Seeder
        $this->info('Seeding default menus...');
        $this->call('db:seed', [
            '--class' => 'Acme\\CmsDashboard\\Database\\Seeders\\MenuSeeder',
            '--force' => true
        ]);

        // 7. Create Admin User
        $this->info('Setting up Administrator user...');
        $email = $this->ask('Enter Admin email', 'admin@admin.com');
        $password = $this->secret('Enter Admin password (min 8 chars)');

        if (empty($password)) {
            $password = 'password';
            $this->info('No password entered. Defaulting to: password');
        }

        $adminRole = Role::where('slug', 'administrator')->first();

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrator',
                'password' => Hash::make($password),
                'role_id' => $adminRole->id,
            ]
        );

        $this->info('Lazy CMS installed successfully!');
        $this->info("Login Email: {$email}");
        $this->info("Login Password: " . (empty($password) ? 'password' : ' [hidden]'));
        $this->info('Login URL: ' . url('/lazy-admin'));
        $this->info('Registration URL: ' . url('/lazy-lazy-registration'));
    }
}
