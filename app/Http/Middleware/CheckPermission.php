<?php

namespace App\Http\Middleware;

use App\Helpers\PermissionHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $checkPermission = Config::get('miscConstant.canManage').Config::get('screenConstants.'.$permission);
        $hasPermission = PermissionHelper::hasPermission($checkPermission);

        if ($hasPermission) {
            return $next($request);
        }

        return redirect('/restricted')->with('error', 'Access not granted.');
    }
}
