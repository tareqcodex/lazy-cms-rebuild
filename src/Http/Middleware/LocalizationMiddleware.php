<?php

namespace Acme\CmsDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Acme\CmsDashboard\Models\Language;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1);
        
        $supportedLocales = Language::where('status', true)->pluck('code')->toArray();

        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
        } else {
            // Check session or default
            $default = Language::where('is_default', true)->first();
            $locale = session('locale', $default->code ?? 'en');
            App::setLocale($locale);
        }

        return $next($request);
    }
}
