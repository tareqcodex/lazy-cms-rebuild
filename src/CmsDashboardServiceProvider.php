<?php

namespace Acme\CmsDashboard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class CmsDashboardServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cms-dashboard');

        Blade::componentNamespace('Acme\\CmsDashboard\\View\\Components', 'cms-dashboard');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/cms-dashboard'),
            ], 'cms-dashboard-views');

            $this->commands([
                \Acme\CmsDashboard\Console\Commands\MakeDashboardPage::class,
            ]);
        }
    }

    public function register(): void
    {
        require_once __DIR__ . '/helpers.php';
    }
}
