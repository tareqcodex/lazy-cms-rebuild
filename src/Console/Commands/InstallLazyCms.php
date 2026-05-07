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

        // 8. Auto-setup User Model Trait & Fillable
        $this->info('Step 8: Setting up User Model traits and permissions...');
        $this->setupUserModel();

        // 9. Create Admin User
        $this->info('Step 9: Setting up Administrator user...');
        $email = $this->ask('Enter Admin email', 'admin@admin.com');
        $password = $this->secret('Enter Admin password (min 8 chars)');

        if (empty($password)) {
            $password = 'password';
            $this->info('No password entered. Defaulting to: password');
        }

        $adminRole = Role::where('slug', 'super-admin')->first();
        
        if (!$adminRole) {
            $adminRole = Role::where('slug', 'administrator')->first();
        }

        if (!$adminRole) {
            $this->error('Admin role not found! Please run php artisan lazy:seed first.');
            return;
        }

        $user = User::where('email', $email)->first() ?: new User();
        $user->forceFill([
            'name' => 'Administrator',
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $adminRole->id,
        ])->save();

        $this->info('---------------------------------------');
        $this->info('Lazy CMS installed successfully!');
        $this->info("Login Email: {$email}");
        $this->info("Login Password: [hidden]");
        $this->info('Login URL: ' . url('/lazy-admin'));
        $this->info('---------------------------------------');
    }

    protected function setupUserModel()
    {
        $path = app_path('Models/User.php');
        if (!file_exists($path)) return;

        $content = file_get_contents($path);

        // 1. Add Use Statement at top if missing
        if (!str_contains($content, 'Acme\CmsDashboard\Traits\HasCmsPermissions')) {
            $content = str_replace(
                'namespace App\Models;',
                "namespace App\Models;\n\nuse Acme\CmsDashboard\Traits\HasCmsPermissions;",
                $content
            );
        }

        // 2. Add role_id to Fillable (Attribute or Property)
        if (!str_contains($content, "'role_id'") && !str_contains($content, '"role_id"')) {
            // PHP 8.3+ Attribute style: #[Fillable(['name', ...])]
            if (str_contains($content, '#[Fillable')) {
                $content = preg_replace('/#\[Fillable\(\[(.*?)\]\)\]/s', "#[Fillable([$1, 'role_id'])]", $content);
            } 
            // Standard property style: protected $fillable = [...]
            elseif (str_contains($content, '$fillable')) {
                $content = preg_replace('/\$fillable\s*=\s*\[(.*?)\]/s', "\$fillable = [$1, 'role_id']", $content);
            }
        }

        // 3. Add HasCmsPermissions Trait inside class
        if (!str_contains($content, 'use HasCmsPermissions')) {
            // If already has other traits, append to them
            if (preg_match('/use (.*?HasFactory.*?;)/s', $content)) {
                $content = preg_replace('/use (.*?HasFactory.*?);/s', "use $1, HasCmsPermissions;", $content);
            } else {
                // Otherwise add after class opening
                $content = preg_replace('/(class User extends.*?{)/s', "$1\n    use HasCmsPermissions;", $content);
            }
        }

        file_put_contents($path, $content);
    }
}
