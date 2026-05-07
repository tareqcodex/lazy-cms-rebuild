<?php

namespace Acme\CmsDashboard\Console\Commands;

use Illuminate\Console\Command;

class LazyList extends Command
{
    protected $signature = 'lazy';
    protected $description = 'List all available Lazy CMS commands';

    public function handle()
    {
        $this->info('---------------------------------------');
        $this->info('      Lazy CMS available commands      ');
        $this->info('---------------------------------------');

        $commands = [
            ['lazy', 'List all available Lazy CMS commands.'],
            ['lazy:install', 'Full installation: migrations, assets, themes, and default data.'],
            ['lazy:update', 'Update system: refresh assets, themes, and sync permissions.'],
            ['lazy:seed', 'Run the main System Sync Seeder for Roles/Permissions.'],
            ['lazy:make-page', 'Create a new custom dashboard page template.'],
            ['vendor:publish --tag=lazy-themes', 'Publish only the frontend themes to resources.'],
            ['vendor:publish --tag=lazy-views', 'Publish all admin views to resources for override.'],
        ];

        $this->table(['Command', 'Description'], $commands);

        $this->info('Usage: php artisan <command>');
        $this->info('---------------------------------------');
    }
}
