<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectPublicToFrontend
{
    public function handle(Request $request, Closure $next): Response
    {
        $frontendUrl = rtrim((string) config('busco.frontend_url'), '/');

        if ($frontendUrl === '') {
            $frontendUrl = 'http://localhost:3000';
        }

        $path = trim($request->path(), '/');
        $target = $path === '' ? $frontendUrl : $frontendUrl . '/' . $path;

        if ($request->getQueryString()) {
            $target .= '?' . $request->getQueryString();
        }

        return redirect()->away($target);
    }
}
