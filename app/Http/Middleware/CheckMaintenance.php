<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = \App\Models\SiteSetting::first();
        if ($settings?->is_maintenance) {
            if ($request->query('secret') === config('app.maintenance_bypass_secret')) {
                return $next($request); 
            }
            if ($request->user()?->is_admin) {
                return $next($request);
            }
            return response()->view('maintenance', [
                'message' => $settings->maintenance_message
            ], 503);
        }
        return $next($request);
    }
}
