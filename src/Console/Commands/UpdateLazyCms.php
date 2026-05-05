<?php

namespace Acme\CmsDashboard\Console\Commands;

use Illuminate\Console\Command;

class UpdateLazyCms extends Command
{
    protected $signature = 'lazy-cms:update';
    protected $description = 'Update Lazy CMS assets, run migrations and clear cache';

    public function handle()
    {
        $this->info('Updating Lazy CMS...');

        // 1. Run Migrations
        $this->info('Running migrations...');
        $this->call('migrate', ['--force' => true]);

        // 2. Sync System Data (Permissions, Roles, Menus)
        $this->info('Syncing system data...');
        $this->call('db:seed', [
            '--class' => 'Acme\\CmsDashboard\\Database\\Seeders\\SystemSyncSeeder',
            '--force' => true
        ]);

        // 3. Publish Assets (Force)
        $this->info('Updating assets...');
        $this->call('vendor:publish', [
            '--provider' => 'Acme\CmsDashboard\CmsDashboardServiceProvider',
            '--force' => true
        ]);

        // 3. Clear Cache
        $this->info('Clearing cache...');
        $this->call('optimize:clear');

        $this->info('Lazy CMS updated successfully!');
    }
}
