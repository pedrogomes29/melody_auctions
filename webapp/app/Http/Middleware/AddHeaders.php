<?php namespace App\Http\Middleware;

use Closure;

// If Laravel >= 5.2 then delete 'use' and 'implements' of deprecated Middleware interface.
class AddHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);


        return $response
                ->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate');
        ;
    }
}