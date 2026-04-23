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

        // Register View Composers for Magic Keys
        $viewMap = [
            'admin.users.edit' => 'users-edit',
            'admin.settings.index' => 'general-settings',
            
            'cms-dashboard::admin.users.edit' => 'users-edit',
            'cms-dashboard::admin.settings.index' => 'general-settings',
        ];

        view()->composer('*', function ($view) use ($viewMap) {
            $viewName = $view->getName();
            $magicKey = $viewMap[$viewName] ?? null;

            if ($magicKey) {
                $dynamicFields = config("lazy-options.hooks.{$magicKey}.fields", []);
                $settings = \Illuminate\Support\Facades\DB::table('cms_settings')->pluck('value', 'key')->toArray();
                $view->with(compact('dynamicFields', 'settings'));
            }
        });

        Blade::componentNamespace('Acme\\CmsDashboard\\View\\Components', 'cms-dashboard');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/cms-dashboard'),
            ], 'cms-dashboard-views');

            $this->commands([
                \Acme\CmsDashboard\Console\Commands\MakeDashboardPage::class,
                \Acme\CmsDashboard\Console\Commands\InstallLazyCms::class,
            ]);
        }
    }

    public function register(): void
    {
        require_once __DIR__ . '/helpers.php';
        $this->mergeConfigFrom(__DIR__ . '/../config/lazy-options.php', 'lazy-options');
    }
}
