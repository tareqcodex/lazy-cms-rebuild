<?php

namespace Acme\CmsDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Acme\CmsDashboard\Models\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        
        // Normalize path (ensure leading slash and trim trailing)
        $normalizedPath = '/' . ltrim($path, '/');
        
        $redirect = Redirect::where('old_url', $normalizedPath)
            ->orWhere('old_url', $path)
            ->first();

        if ($redirect) {
            // Update stats
            $redirect->increment('hits');
            $redirect->update(['last_hit_at' => now()]);

            return redirect($redirect->new_url, $redirect->status_code);
        }

        return $next($request);
    }
}
