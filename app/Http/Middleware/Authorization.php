<?php

namespace App\Http\Middleware;

use App\Models\RolesPermissions;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userPermissions = RolesPermissions::whereHas('user', function ($q) {
            $q->where('id', Auth::user()->id);
        })
            ->with(['role' => function ($r) {
                $r->select('id', 'name');
            }])
            ->with(['permission' => function ($p) {
                $p->select('id', 'name');
            }])
            ->first();
        if ($userPermissions && $userPermissions->role->name == 'editor' && in_array($userPermissions->permission->name, config('constants.Roles_Permissions.Editor'))) {
            return $next($request);
        }
        return response()->json('Sorry, You do not have access!!');
    }
}
