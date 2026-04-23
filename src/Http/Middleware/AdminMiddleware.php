<?php

namespace Acme\CmsDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Acme\CmsDashboard\Models\BlockedIp;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check IP Block
        $isIpBlocked = BlockedIp::where('ip_address', $request->ip())
            ->where('attempts', '>=', 5)
            ->exists();

        if ($isIpBlocked) {
            abort(403, 'You do not have permission to access this page. Your IP has been blocked.');
        }

        // Exclude logic
        $login_slug = get_cms_option('login_url', 'super-lazy-admin');
        $register_slug = get_cms_option('register_url', 'super-lazy-register');

        if ($request->is('admin/login*') || $request->is('admin/register*') || 
            $request->is('admin/login/check') || $request->is('admin/email/check') ||
            $request->is($login_slug . '*') || $request->is($register_slug . '*')) {
            return $next($request);
        }

        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to access the admin panel.');
        }

        $user = auth()->user()->fresh();
        if ($user && ($user->is_blocked || ($user->blocked_until && $user->blocked_until->isFuture()))) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->withErrors(['email' => 'Your account has been blocked. Please contact the administrator.']);
        }

        // Allow all authenticated users into the admin prefix. 
        // Granular permissions are handled inside controllers and sidebar.
        return $next($request);
    }
}
