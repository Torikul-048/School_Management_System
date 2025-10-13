<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class EnsureParentHasChildren
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if authenticated user is a parent
        if (!Auth::check() || !Auth::user()->hasRole('Parent')) {
            abort(403, 'Unauthorized access.');
        }

        // Check if parent has any children linked
        $hasChildren = Student::where('parent_user_id', Auth::id())
            ->where('status', 'active')
            ->exists();

        if (!$hasChildren) {
            return redirect()->route('dashboard')
                ->with('error', 'No children found linked to your account. Please contact administration to link your children.');
        }

        return $next($request);
    }
}
