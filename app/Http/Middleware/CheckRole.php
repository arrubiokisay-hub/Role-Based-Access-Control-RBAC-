<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if the user is logged in using your app's session variables
        if (!session()->has('username') || !session()->has('role')) {
            return redirect()->route('user.login');
        }

        $userRole = session('role');

        // 2. Check if the session role is allowed for this route
        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}