<?php

namespace Acme\CmsDashboard\Console\Commands;

use Illuminate\Console\Command;

class SeedLazyCms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazy:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Lazy CMS default menus and initial data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding Lazy CMS data...');

        try {
            $this->call('db:seed', [
                '--class' => 'Acme\\CmsDashboard\\Database\\Seeders\\MenuSeeder',
                '--force' => true
            ]);
            
            $this->info('Lazy CMS seeding completed successfully!');
        } catch (\Exception $e) {
            $this->error('Seeding failed: ' . $e->getMessage());
        }
    }
}
