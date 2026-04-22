<?php

use Illuminate\Support\Facades\Route;
use Acme\CmsDashboard\Http\Controllers\Admin\PostController;
use Acme\CmsDashboard\Http\Controllers\Admin\PostTypeController;
use Acme\CmsDashboard\Http\Controllers\Admin\MediaController;
use Acme\CmsDashboard\Http\Controllers\Admin\DashboardController;
use Acme\CmsDashboard\Http\Controllers\Admin\CustomFieldController;
use Acme\CmsDashboard\Http\Controllers\Admin\UserController;
use Acme\CmsDashboard\Http\Controllers\Admin\LoginController;
use Acme\CmsDashboard\Http\Controllers\Admin\RegisterController;
use Acme\CmsDashboard\Http\Controllers\Admin\RoleController;
use Acme\CmsDashboard\Http\Controllers\Admin\AcptCptController;
use Acme\CmsDashboard\Http\Controllers\Admin\AcptTaxonomyController;
use Acme\CmsDashboard\Http\Controllers\Admin\AcptTermController;
use Acme\CmsDashboard\Http\Controllers\FrontendController;

// Dynamic Login & Registration URLs from Settings (Highest Priority)
Route::middleware(['web'])->group(function() {
    try {
        $login_slug = get_cms_option('login_url', 'super-lazy-admin');
        $register_slug = get_cms_option('register_url', 'super-lazy-register');

        Route::get($login_slug, [LoginController::class, 'showLoginForm']);
        Route::get($register_slug, [RegisterController::class, 'showRegistrationForm']);
    } catch (\Exception $e) {}
});

Route::prefix('admin')->name('admin.')->middleware(['web', \Acme\CmsDashboard\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Media and posts
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::get('media/upload', [MediaController::class, 'create'])->name('media.create');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('edit-post', [PostController::class, 'edit'])->name('edit-post');
    Route::post('posts/bulk', [PostController::class, 'bulk'])->name('posts.bulk');
    Route::post('categories/bulk', [\Acme\CmsDashboard\Http\Controllers\Admin\CategoryController::class, 'bulk'])->name('categories.bulk');
    Route::post('tags/bulk', [\Acme\CmsDashboard\Http\Controllers\Admin\TagController::class, 'bulk'])->name('tags.bulk');
    Route::post('posts/{post}/restore', [PostController::class, 'restore'])->name('posts.restore')->withTrashed();
    Route::delete('posts/{post}/force-delete', [PostController::class, 'forceDelete'])->name('posts.force-delete')->withTrashed();
    Route::resource('posts', PostController::class);
    Route::get('builder/{id}', [PostController::class, 'builder'])->name('builder');
    Route::post('builder/{id}/save', [PostController::class, 'saveBuilder'])->name('builder.save');
    Route::get('builder/{id}/preview', [PostController::class, 'previewBuilder'])->name('builder.preview');

    Route::post('pages/bulk', [\Acme\CmsDashboard\Http\Controllers\Admin\PageController::class, 'bulk'])->name('pages.bulk');
    Route::post('pages/{page}/restore', [\Acme\CmsDashboard\Http\Controllers\Admin\PageController::class, 'restore'])->name('pages.restore')->withTrashed();
    Route::delete('pages/{page}/force-delete', [\Acme\CmsDashboard\Http\Controllers\Admin\PageController::class, 'forceDelete'])->name('pages.force-delete')->withTrashed();
    Route::resource('pages', \Acme\CmsDashboard\Http\Controllers\Admin\PageController::class);
    Route::resource('categories', \Acme\CmsDashboard\Http\Controllers\Admin\CategoryController::class);
    Route::resource('tags', \Acme\CmsDashboard\Http\Controllers\Admin\TagController::class);
    Route::resource('post-types', PostTypeController::class)->only(['index', 'store', 'destroy']);
    
    Route::post('categories/ajax', function(\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
        
        $category = \Acme\CmsDashboard\Models\Category::create([
            'name' => $validated['name'],
            'parent_id' => !empty($validated['parent_id']) ? $validated['parent_id'] : null
        ]);
        
        return response()->json($category);
    })->name('categories.ajax');

    // Navigation Menus
    Route::resource('menus', \Acme\CmsDashboard\Http\Controllers\Admin\MenuManagementController::class);
    
    // Dynamic Taxonomy Terms
    Route::get('taxonomies/{slug}/terms', [\Acme\CmsDashboard\Http\Controllers\Admin\TaxonomyTermController::class, 'index'])->name('old.terms.index');
    Route::post('taxonomies/{slug}/terms', [\Acme\CmsDashboard\Http\Controllers\Admin\TaxonomyTermController::class, 'store'])->name('old.terms.store');
    Route::delete('taxonomies/{slug}/terms/{id}', [\Acme\CmsDashboard\Http\Controllers\Admin\TaxonomyTermController::class, 'destroy'])->name('old.terms.destroy');
    Route::post('taxonomies/{slug}/terms/bulk', [\Acme\CmsDashboard\Http\Controllers\Admin\TaxonomyTermController::class, 'bulk'])->name('old.terms.bulk');

    // Advanced Custom Post Types (ACPT) - Latest Version
    Route::prefix('acpt')->name('acpt.')->group(function() {
        Route::post('cpt/bulk', [AcptCptController::class, 'bulk'])->name('cpt.bulk');
        Route::post('cpt/{id}/toggle-status', [AcptCptController::class, 'toggleStatus'])->name('cpt.toggle-status');
        Route::post('cpt/{id}/duplicate', [AcptCptController::class, 'duplicate'])->name('cpt.duplicate');
        Route::resource('cpt', AcptCptController::class);
        
        Route::post('taxonomies/bulk', [AcptTaxonomyController::class, 'bulk'])->name('taxonomies.bulk');
        Route::resource('taxonomies', AcptTaxonomyController::class)->except(['show']);
        Route::post('tax-terms/ajax', [AcptTermController::class, 'ajax'])->name('terms.ajax');
        Route::get('tax-terms/{taxonomySlug}', [AcptTermController::class, 'index'])->name('terms.index');
        Route::post('tax-terms/{taxonomySlug}/bulk', [AcptTermController::class, 'bulk'])->name('terms.bulk');
        Route::post('tax-terms/{taxonomySlug}', [AcptTermController::class, 'store'])->name('terms.store');
        Route::get('tax-terms/{taxonomySlug}/edit/{id}', [AcptTermController::class, 'edit'])->name('terms.edit');
        Route::put('tax-terms/{taxonomySlug}/{id}', [AcptTermController::class, 'update'])->name('terms.update');
        Route::delete('tax-terms/{taxonomySlug}/{id}', [AcptTermController::class, 'destroy'])->name('terms.destroy');
        Route::delete('fields/delete-field/{field}', [CustomFieldController::class, 'deleteField'])->name('fields.delete-field');
        Route::post('fields/store-field', [CustomFieldController::class, 'storeField'])->name('fields.store-field');
        Route::resource('fields', CustomFieldController::class);
    });

    // Dashboard index
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // Users
    Route::get('profile', function() {
        return redirect()->route('admin.users.edit', auth()->id());
    })->name('profile');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    // Settings
    Route::get('settings', [DashboardController::class, 'settings'])->name('settings.index');
    Route::post('settings', [DashboardController::class, 'updateSettings'])->name('settings.update');

    // Auth
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('login/check', [LoginController::class, 'checkCredentials'])->name('login.check');
    Route::post('admin/login/check', [LoginController::class, 'checkCredentials'])->name('admin.login.check');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('email/check', [RegisterController::class, 'checkEmail'])->name('email.check');
    Route::post('admin/email/check', [RegisterController::class, 'checkEmail'])->name('admin.email.check');

    // DB Fix / Seeding
    Route::get('fix-db', function() {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--class' => 'Acme\\CmsDashboard\\Database\\Seeders\\MenuSeeder', 
                '--force' => true
            ]);
            
            $pagesMenu = \Acme\CmsDashboard\Models\Menu::where('title', 'Pages')->first();
            if ($pagesMenu) {
                \Acme\CmsDashboard\Models\Menu::where('parent_id', $pagesMenu->id)->delete();
                \Acme\CmsDashboard\Models\Menu::create(['parent_id' => $pagesMenu->id, 'title' => 'All Pages', 'route' => 'admin.pages.index', 'order' => 1]);
                \Acme\CmsDashboard\Models\Menu::create(['parent_id' => $pagesMenu->id, 'title' => 'Add New', 'route' => 'admin.pages.create', 'order' => 2]);
            }
            
            $usersMenu = \Acme\CmsDashboard\Models\Menu::where('title', 'Users')->first();
            if ($usersMenu) {
                \Acme\CmsDashboard\Models\Menu::updateOrCreate(['parent_id' => $usersMenu->id, 'title' => 'Roles'], ['route' => 'admin.roles.index', 'order' => 3]);
            }

            return "Database and Menus fixed successfully!";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    });

    // Frontend Routes (Catch-all for posts/pages)
    Route::get('/{typeOrSlug}/{slug?}', [FrontendController::class, 'show'])->name('frontend.show');

});
