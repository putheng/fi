<?php
/**
 * Created by PhpStorm.
 * User: somkiat
 * Date: 03/02/2017
 * Time: 14:08
 */

namespace App\Http\Middleware;

use App\Http\Common\ORAConsts;
use Closure;
use Sentinel;
use Redirect;

class PermissionAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Sentinel::getUser()->inRole(ORAConsts::ROLE_ADMIN_SLUG) &&
            !Sentinel::getUser()->inRole(ORAConsts::ROLE_LIMITED_ADMIN_SLUG) &&
            !Sentinel::getUser()->inRole(ORAConsts::ROLE_STRONG_ADMIN_SLUG)
        ) {
            return redirect()->route('admin.dashboard');
        }
        return $next($request);
    }
}