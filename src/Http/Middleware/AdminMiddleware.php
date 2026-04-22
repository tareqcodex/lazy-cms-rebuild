<?php

namespace Acme\CmsDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
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

        // Allow all authenticated users into the admin prefix. 
        // Granular permissions are handled inside controllers and sidebar.
        return $next($request);
    }
}
