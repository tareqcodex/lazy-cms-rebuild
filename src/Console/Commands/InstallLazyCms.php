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

        // 3. Create Storage Link
        $this->info('Creating storage link...');
        $this->call('storage:link');

        // 4. Set Default Options
        $this->info('Setting up default configurations...');
        $options = [
            'login_url' => 'lazy-admin',
            'register_url' => 'lazy-registration',
            'login_theme' => 'breeze',
            'register_theme' => 'breeze',
        ];

        foreach ($options as $key => $value) {
            DB::table('cms_settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        }

        // 5. Sync System Data (Permissions, Roles, Menus)
        $this->info('Syncing system data...');
        $this->call('db:seed', [
            '--class' => 'Acme\\CmsDashboard\\Database\\Seeders\\SystemSyncSeeder',
            '--force' => true
        ]);

        // 6. Create Admin User
        $this->info('Setting up Administrator user...');
        $email = $this->ask('Enter Admin email', 'admin@admin.com');
        $password = $this->secret('Enter Admin password (min 8 chars)');

        if (empty($password)) {
            $password = 'password';
            $this->info('No password entered. Defaulting to: password');
        }

        $adminRole = Role::where('slug', 'super-admin')->first();

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
        $this->info('Registration URL: ' . url('/lazy-registration'));
    }
}
