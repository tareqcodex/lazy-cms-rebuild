<?php

namespace Acme\CmsDashboard\Console\Commands;

use Illuminate\Console\Command;
use Acme\CmsDashboard\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InstallLazyCms extends Command
{
    protected $signature = 'lazy:install';
    protected $description = 'Full installation of Lazy CMS: migrations, assets, themes, and default data.';

    public function handle()
    {
        $this->info('--- Starting Lazy CMS Installation ---');

        // 1. Run Migrations
        $this->info('Step 1: Running migrations...');
        $this->call('migrate', ['--force' => true]);

        // 2. Publish Assets
        $this->info('Step 2: Publishing dashboard assets...');
        $this->call('vendor:publish', [
            '--tag' => 'cms-dashboard-assets',
            '--force' => true
        ]);

        // 3. Publish Themes
        $this->info('Step 3: Publishing themes to resources/views/themes...');
        $this->call('vendor:publish', [
            '--tag' => 'lazy-themes',
            '--force' => true
        ]);

        // 4. Create Storage Link
        $this->info('Step 4: Creating storage link...');
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        // 5. Set Default Options
        $this->info('Step 5: Setting up default configurations...');
        $options = [
            'login_url' => 'lazy-admin',
            'register_url' => 'lazy-registration',
            'login_theme' => 'breeze',
            'register_theme' => 'breeze',
            'active_theme' => 'lazy-theme',
        ];

        foreach ($options as $key => $value) {
            DB::table('cms_settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        }

        // 6. Sync System Data (Permissions, Roles, Menus)
        $this->info('Step 6: Syncing Roles, Permissions and Menus...');
        $this->call('db:seed', [
            '--class' => 'Acme\\CmsDashboard\\Database\\Seeders\\SystemSyncSeeder',
            '--force' => true
        ]);

        // 7. Sync Languages
        $this->info('Step 7: Syncing Languages...');
        $this->call('db:seed', [
            '--class' => 'Acme\\CmsDashboard\\Database\\Seeders\\LanguageSeeder',
            '--force' => true
        ]);

        // 8. Create Admin User
        $this->info('Step 8: Setting up Administrator user...');
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

        $this->info('---------------------------------------');
        $this->info('Lazy CMS installed successfully!');
        $this->info("Login Email: {$email}");
        $this->info("Login Password: [hidden]");
        $this->info('Login URL: ' . url('/lazy-admin'));
        $this->info('---------------------------------------');
    }
}
