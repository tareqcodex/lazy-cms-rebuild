<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Acme\CmsDashboard\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => [
                'label' => 'Total Posts',
                'count' => Post::where('type', 'post')->count(),
                'change' => '+4.2%'
            ],
            'total_pages' => [
                'label' => 'Total Pages',
                'count' => Post::where('type', 'page')->count(),
                'change' => '+1.5%'
            ],
            'total_users' => [
                'label' => 'Total Users',
                'count' => User::count(),
                'change' => '+2.1%'
            ],
            'blocked_users' => [
                'label' => 'Blocked Users',
                'count' => User::where('is_blocked', true)->orWhere(function($q){
                    $q->whereNotNull('blocked_until')->where('blocked_until', '>', now());
                })->count(),
                'change' => 'Security'
            ],
            'blacklisted_ips' => [
                'label' => 'Blacklisted IPs',
                'count' => \Acme\CmsDashboard\Models\BlockedIp::count(),
                'change' => 'Protection'
            ],
            'media_count' => [
                'label' => 'Media Assets',
                'count' => DB::table('media')->count(),
                'change' => '+12.3%'
            ],
            'main_chart' => [
                'labels' => ['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'data1' => [30, 45, 35, 50, 40, 60, 55],
                'data2' => [20, 30, 25, 40, 30, 45, 40]
            ],
            'traffic_stats' => [
                'labels' => ['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'impressions' => [300, 450, 350, 500, 400, 600, 550],
                'visitors' => [200, 300, 250, 400, 300, 450, 400],
                'conversion_rate' => [
                    'value' => '24.8%',
                    'change' => '-2.4%'
                ]
            ]
        ];

        return view('cms-dashboard::admin.dashboard', compact('stats'));
    }

    public function settings()
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }
        
        $pages = Post::where('type', 'page')->where('status', 'published')->orderBy('title')->get();
        $settings = DB::table('cms_settings')->pluck('value', 'key')->toArray();

        return view('cms-dashboard::admin.settings.index', compact('pages', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }
        $data = $request->except('_token');
        
        // Handle Checkboxes
        $data['users_can_register'] = $request->has('users_can_register') ? '1' : '0';
        $data['image_auto_webp'] = $request->has('image_auto_webp') ? '1' : '0';
        $data['enable_page_cache'] = $request->has('enable_page_cache') ? '1' : '0';

        // Sanitize Slugs
        if (isset($data['login_url'])) $data['login_url'] = Str::slug($data['login_url']);
        if (isset($data['register_url'])) $data['register_url'] = Str::slug($data['register_url']);

        foreach ($data as $key => $value) {
            DB::table('cms_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        lazy_log_activity('settings_updated', "Updated CMS settings");

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function seoSettings()
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }
        
        $settings = DB::table('cms_settings')->pluck('value', 'key')->toArray();
        return view('cms-dashboard::admin.settings.seo', compact('settings'));
    }

    public function updateSeoSettings(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }
        
        $data = $request->except('_token');
        
        // Handle Sitemap Checkboxes
        $checkboxes = ['sitemap_include_posts', 'sitemap_include_pages', 'sitemap_include_categories', 'sitemap_include_tags', 'noindex', 'nofollow'];
        
        // Dynamic CPT sitemap checkboxes
        try {
            $cpts = \Acme\CmsDashboard\Models\PostType::where('is_builtin', false)->pluck('slug');
            foreach ($cpts as $slug) {
                $checkboxes[] = 'sitemap_include_cpt_' . $slug;
            }
        } catch (\Exception $e) {}

        foreach ($checkboxes as $box) {
            $data[$box] = $request->has($box) ? '1' : '0';
        }
        
        foreach ($data as $key => $value) {
            DB::table('cms_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        return redirect()->back()->with('success', 'SEO Settings updated successfully!');
    }

    public function getRelatedPosts(Request $request)
    {
        $search = $request->query('s');
        $excludeId = $request->query('exclude');
        
        if (!$search) return response()->json([]);

        $posts = \Acme\CmsDashboard\Models\Post::where('status', 'published')
            ->where('id', '!=', $excludeId)
            ->where('title', 'like', '%' . $search . '%')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'type']);

        $posts->map(function($post) {
            $prefix = ($post->type === 'post' || $post->type === 'page') ? '' : $post->type . '/';
            $post->url = url('/' . $prefix . $post->slug);
            return $post;
        });

        return response()->json($posts);
    }

    public function activityLogs(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }

        $query = \Acme\CmsDashboard\Models\ActivityLog::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(20);
        $users = User::all();

        return view('cms-dashboard::admin.settings.activity-logs', compact('logs', 'users'));
    }

    public function documentation()
    {
        return view('cms-dashboard::admin.documentation');
    }
}
