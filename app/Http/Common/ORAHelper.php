<?php
/**
 * Created by PhpStorm.
 * User: mpravato
 * Date: 2/2/2018
 * Time: 12:26 PM
 */

namespace App\Http\Common;


use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class ORAHelper
{

    public static function isAdminAny()
    {
        $user = Sentinel::getUser();
        return ORAHelper::isSuperAdmin($user) || ORAHelper::isLimitedAdmin($user) || ORAHelper::isStrongAdmin($user);
    }

    public static function isSuperAdmin()
    {
        $user = Sentinel::getUser();
        return $user->inRole(ORAConsts::ROLE_ADMIN_SLUG);
    }

    public static function isLimitedAdmin()
    {
        $user = Sentinel::getUser();
        return $user->inRole(ORAConsts::ROLE_LIMITED_ADMIN_SLUG);
    }

    public static function isStrongAdmin()
    {
        $user = Sentinel::getUser();
        return $user->inRole(ORAConsts::ROLE_STRONG_ADMIN_SLUG);
    }

    public static function isSingleClinicUser()
    {
        $user = Sentinel::getUser();
        return $user->inRole(ORAConsts::ROLE_USER_SINGLE_SLUG);
    }

    public static function isMultiClinicUser()
    {
        $user = Sentinel::getUser();
        return $user->inRole(ORAConsts::ROLE_USER_MULTI_SLUG);
    }

    public static function getIndexUrl($resCodeLong)
    {
        $url = env("FE_APP_URL");
        $url .= "/800";
        $url .= "?q=" . $resCodeLong;
        return $url;
    }

}