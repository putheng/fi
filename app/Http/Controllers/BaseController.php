<?php namespace App\Http\Controllers;

use App\Http\Common\ORAConsts;
use App\Models\Organization;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Securimage;
use View;
use Cookie;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    /**
     * Message bag.
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $messageBag = null;

    /**
     * Initializer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->messageBag = new MessageBag;

    }

    /**
     * Crop Demo
     */
    public function crop_demo()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $targ_w = $targ_h = 150;
            $jpeg_quality = 99;

            $src = base_path() . '/public/assets/img/cropping-image.jpg';
            //dd($src);
            $img_r = imagecreatefromjpeg($src);

            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, intval($_POST['x']), intval($_POST['y']), $targ_w, $targ_h, intval($_POST['w']), intval($_POST['h']));

            header('Content-type: image/jpeg');
            imagejpeg($dst_r, null, $jpeg_quality);

            exit;
        }
    }

    public function showFrontEndView($name = null)
    {

        if (View::exists($name)) {
            return view($name);
        } else {
            return view('404');
        }
    }

    public function secureImage(Request $request)
    {
        session_start();
        include_once public_path() . "/assets/vendors/secureimage/securimage.php";
        $securimage = new Securimage();
        if ($securimage->check($request->captcha_code) == false) {
            echo "The security code entered was incorrect.<br /><br />";
            echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
            exit;
        } else {
            echo "The security code entered was correct. <a href='javascript:history.go(-1)'>back</a><br /><br />";
            exit;
        }

    }

    public function mysqlToDisplayDate($mysqlDate)
    {
        if ($mysqlDate != null && $mysqlDate != '') {
            $time = new \DateTime($mysqlDate);
            return $time->format($this->getDisplayDateFormat());
        }
        return null;
    }

    public function mysqlToDisplayDateTime($mysqlDate, $includeTime = true)
    {
        if ($mysqlDate != null && $mysqlDate != '') {
            $time = new \DateTime($mysqlDate);
            return $time->format($this->getDisplayDateTimeFormat($includeTime));
        }
        return null;
    }

    public function getDisplayDateFormat()
    {
        return ORAConsts::DISPLAY_DATE_FORMAT;
    }

    public function getDisplayDateTimeFormat($includeTime = true)
    {
        if ($includeTime) {
            return ORAConsts::DISPLAY_DATETIME_S_FORMAT;
        }
        return ORAConsts::DISPLAY_DATETIME_FORMAT;
    }

    public function setLocalTimezone()
    {
        date_default_timezone_set(ORAConsts::TIMEZONE);
    }

    public function getLocalTimezone()
    {
        return new \DateTimeZone(ORAConsts::TIMEZONE);
    }

    public function completeUrl(&$url)
    {
        if (0 === strpos($url, 'http://')) {
            $url = 'http://' . $url;
        }
    }

    public function getGridDeleteButton($route)
    {
        $html = '<a  href="' . $route . '" data-toggle="modal" data-target="#delete_confirm" class="btn btn-default btn-xs red-stripe action-btn" style="margin-left: 8px">';
        $html .= '<i class="fa fa-edit" style="margin-right: 4px"></i>';
        $html .= Lang::get('app/general.delete');
        $html .= '</a>';
        return $html;
    }

    public function getGridEditButton($route)
    {
        $html = '<a  href="' . $route . '" class="btn btn-default btn-xs orange-stripe action-btn" >';
        $html .= '<i class="fa fa-edit" style="margin-right: 4px"></i>';
        $html .= Lang::get('app/general.edit');
        $html .= '</a>';
        return $html;
    }

    public function isAdminAny($user)
    {
        return $this->isSuperAdmin($user) || $this->isLimitedAdmin($user) || $this->isStrongAdmin($user);
    }

    public function isSuperAdmin($user)
    {
        return $user->inRole(ORAConsts::ROLE_ADMIN_SLUG);
    }

    public function isLimitedAdmin($user)
    {
        return $user->inRole(ORAConsts::ROLE_LIMITED_ADMIN_SLUG);
    }

    public function isStrongAdmin($user)
    {
        return $user->inRole(ORAConsts::ROLE_STRONG_ADMIN_SLUG);
    }

    public function isSingleClinicUser($user)
    {
        return $user->inRole(ORAConsts::ROLE_USER_SINGLE_SLUG);
    }

    public function isMultiClinicUser($user)
    {
        return $user->inRole(ORAConsts::ROLE_USER_MULTI_SLUG);
    }



}