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
            'client_rating' => [
                'label' => 'Total Posts',
                'score' => Post::count(),
                'change' => '+12.5%'
            ],
            'instagram_followers' => [
                'label' => 'Total Users',
                'count' => User::count(),
                'change' => '-1.2%'
            ],
            'total_revenue' => [
                'label' => 'Media Library',
                'amount' => DB::table('media')->count(),
                'change' => '+8.4%'
            ],
            'main_chart' => [
                'labels' => ['Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'data1' => [30, 45, 35, 50, 40, 60, 55],
                'data2' => [20, 30, 25, 40, 30, 45, 40]
            ],
            'traffic_stats' => [
                'new_subscribers' => [
                    'value' => Post::where('type', 'page')->count(),
                    'change' => '+15%',
                    'data' => [10, 15, 8, 20, 18, 25, 22]
                ],
                'conversion_rate' => [
                    'value' => '24.8%',
                    'change' => '-2.4%',
                    'data' => [20, 18, 15, 22, 20, 18, 16]
                ],
                'bounce_rate' => [
                    'value' => '42.5%',
                    'change' => '+0.8%',
                    'data' => [40, 42, 45, 41, 43, 40, 42]
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
        $settings = DB::table('cms_settings')->pluck('value', 'key')->toArray();
        return view('cms-dashboard::admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }
        $data = $request->except('_token');
        
        // Handle Checkboxes
        $data['users_can_register'] = $request->has('users_can_register') ? '1' : '0';

        // Sanitize Slugs
        if (isset($data['login_url'])) $data['login_url'] = Str::slug($data['login_url']);
        if (isset($data['register_url'])) $data['register_url'] = Str::slug($data['register_url']);

        foreach ($data as $key => $value) {
            DB::table('cms_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        try {
            Artisan::call('route:clear');
            Artisan::call('config:clear');
        } catch (\Exception $e) { }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
