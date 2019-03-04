<?php namespace App\Http\Common;

class ORAConsts
{
    const LANGUAGE1 = "en";
    const LANGUAGE2 = "hi";

    const DISPLAY_DATE_FORMAT = "d-m-Y";
    const DISPLAY_DATETIME_FORMAT = "d-m-Y H:i";
    const DISPLAY_DATETIME_S_FORMAT = "d-m-Y H:i:s";

    const TIMEZONE = "Asia/Calcutta";

    const ROLE_ADMIN_SLUG = "admin";
    const ROLE_LIMITED_ADMIN_SLUG = "limitadmin";
    const ROLE_STRONG_ADMIN_SLUG = "strongadmin";
    const ROLE_USER_SINGLE_SLUG = "user";
    const ROLE_USER_MULTI_SLUG = "multiuser";

    const ROLE_ADMIN_ID = 1;
    const ROLE_USER_SINGLE_ID = 2;
    const ROLE_USER_MULTI_ID = 3;
    const ROLE_LIMITED_ADMIN_ID = 4;
    const ROLE_STRONG_ADMIN_ID = 5;

    const RES_STI_STATUS_EMPTY = -1;
    const RES_STI_STATUS_COUNT = 11;

    const RES_SCREENED_STATUS_EMPTY = -1;
    const RES_SCREENED_STATUS_NEGATIVE = 0;
    const RES_SCREENED_STATUS_POSITIVE = 1;

    const RES_CONFIRMED_STATUS_EMPTY = -1;
    const RES_CONFIRMED_STATUS_NEGATIVE = 0;
    const RES_CONFIRMED_STATUS_POSITIVE = 1;
    const RES_CONFIRMED_STATUS_OTHER = 2;

    const SMS_STATUS_RETRY = "RETRY";
    const SMS_STATUS_SUCCESS = "SUCCESS";
    const SMS_STATUS_ERROR = "ERROR";
    const SMS_MAX_RETRY = 3;

    const RES_STATUS_ASSESSMENT = 0;
    const RES_STATUS_RESERVATION = 1;
}

