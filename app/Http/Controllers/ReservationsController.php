<?php namespace App\Http\Controllers;

use App\Http\Common\ORAConsts;
use App\Http\Common\ORAHelper;
use App\Models\Clinic;
use App\Models\Reservation;
use ied3vil\LanguageSwitcher\Facades\LanguageSwitcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Sentinel;
use QrCode;
use App\Helpers\Textlocal;

class ReservationsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission_user');
    }

    public function index(Request $request, $clinicId = 0)
    {
        //TEST:
        //$this->testSmsSend();

        $user = Sentinel::getUser();
        if ($this->isAdminAny($user)) {

            //Prepare data for the combobox
            $clinics = Clinic::getEnabledCombo();

            //If passed nothing, then select the first clinic from the available
            if ($clinicId == 0) {
                $clinicId = $request->input('clinic_id');
                if ($clinicId == '' || $clinicId == 0) {
                    //Find the first clinic in the array
                    $site = key($clinics);
                    $clinicId = key($clinics[$site]);
                }
            }
        } else if ($this->isMultiClinicUser($user)) {

            //Only the clinics assigned to the user
            $usersClinics = $user->usersClinics()->pluck('clinic_id');
            $clinics = Clinic::getUserClinicsCombo($usersClinics);

            //If passed nothing, then select the first clinic from the available
            if ($clinicId == 0) {
                $clinicId = $request->input('clinic_id');
                if ($clinicId == '' || $clinicId == 0) {
                    //Find the first clinic in the array
                    $site = key($clinics);
                    $clinicId = key($clinics[$site]);
                }
            }

            //Make sure the clinic is available to the user (could hack a different clinic code)
            if (!in_array($clinicId, $usersClinics->toArray())) {
                //Find the first clinic in the array
                $site = key($clinics);
                $clinicId = key($clinics[$site]);
            }
        } else {
            //Only the clinic assigned to the user is visible, no matter what is passed
            $clinicId = $user->clinic_id;
            $clinics = [];
        }

        $clinic = Clinic::getById($clinicId);

        //Check values coming from the page
        $dateStart = $request->input("dateStart", null);
        $dateEnd = $request->input("dateEnd", null);
        //Convert to DateTime if needed
        if ($dateStart != null && $dateStart != '') {
            $dateStart = \DateTime::createFromFormat('Y-m-d', $dateStart);
        }
        if ($dateEnd != null && $dateEnd != '') {
            $dateEnd = \DateTime::createFromFormat('Y-m-d', $dateEnd);
        }
        $dateAction = $request->input("dateAction", '');

        $dates = $this->getDates($clinicId, $dateStart, $dateEnd, $dateAction);
        $this->loadReservations($clinicId, $dates, $dateStart, $dateEnd);

        $dateStartDisplay = $dateStart->format('d-m-Y');
        $dateEndDisplay = $dateEnd->format('d-m-Y');

        $reservationActionsDisabled = $this->isLimitedAdmin($user) || $this->isStrongAdmin($user);
        $reservationStatusHidden = $this->isLimitedAdmin($user);
        $changeStatusLimited = $this->isSingleClinicUser($user);
        
        return view('admin.reservations.index', compact('clinics'))
            ->with('dates', $dates)
            ->with('dateStart', $dateStart)
            ->with('dateEnd', $dateEnd)
            ->with('dateStartDisplay', $dateStartDisplay)
            ->with('dateEndDisplay', $dateEndDisplay)
            ->with('reservationActionsDisabled', $reservationActionsDisabled)
            ->with('reservationStatusHidden', $reservationStatusHidden)
            ->with('changeStatusLimited', $changeStatusLimited)
            ->with('clinic', $clinic);
    }

    public function loadReservations($clinicId, &$dates, $dateStart, $dateEnd)
    {
        //Load the reservations for the time period
        $reservations = DB::table("reservations")
            ->join('tokens', 'reservations.token_id', '=', 'tokens.id')
            ->leftJoin('reservations_assessments', 'reservations.id', '=', 'reservations_assessments.reservation_id')
            ->select('reservations.*',
                'tokens.token_num',
                'reservations_assessments.gender'
            )
            ->where('reservations.res_status', '=', ORAConsts::RES_STATUS_RESERVATION)
            ->where('reservations.clinic_id', '=', $clinicId)
            ->where('reservations.res_date', '>=', $dateStart->format('Y-m-d H:i:s'))
            ->where('reservations.res_date', '<=', $dateEnd->format('Y-m-d H:i:s'))
            ->whereNull('reservations.deleted_at')
            ->orderBy('reservations.res_date')
            ->get();

        //SERVICES REMOVED FOR NOW
//        // Get the services
//        $services = DB::table("reservations")
//            ->leftJoin('reservations_services', 'reservations_services.reservation_id', '=', 'reservations_services.reservation_id')
//            ->join('clinics_services', 'reservations_services.service_id', '=', 'clinics_services.id')
//            ->select('reservations_services.*', 'clinics_services.service_desc_lang1', 'clinics_services.service_desc_lang2')
//            ->where('reservations.clinic_id', '=', $clinicId)
//            ->where('reservations.res_date', '>=', $dateStart->format('Y-m-d H:i:s'))
//            ->where('reservations.res_date', '<=', $dateEnd->format('Y-m-d H:i:s'))
//            ->whereNull('reservations.deleted_at')
//            ->distinct()
//            ->get()
//            ->toArray();
//
//        // Assign the service to the reservation
//        foreach ($reservations as &$reservation) {
//            // Init
//            $reservation->services = null;
//
//            // Fill
//            if ($services != null && count($services) > 0) {
//                // Filter the services
//                $filtered = array_filter($services, function ($element) use ($reservation) {
//                    return $element->reservation_id === $reservation->id;
//                });
//
//                // Check
//                if ($filtered != null && count($filtered) > 0) {
//                    // Extract the language column
//                    $column = LanguageSwitcher::getCurrentLanguage() == ORAConsts::LANGUAGE2 ? "service_desc_lang2" : "service_desc_lang1";
//                    $language = array_column($filtered, $column);
//
//                    // Join
//                    $reservation->services = implode(", ", $language);
//                }
//            }
//        }


        //Prepare the "status" column
        foreach ($reservations as &$reservation) {
            $this->loadReservationStatus($reservation);
            if ($reservation->gender != null && $reservation->gender != '') {
                $reservation->gender = $this->translateCode($reservation->gender, array('male', 'female', 'tg', 'complicated', 'not_say'));
            }
        }

        //Assign the reservations to each date
        foreach ($dates as &$date) {
            $dateStr = $date["date"]->format("Y-m-d");
            $dateReservations = $date["reservations"];

            foreach ($reservations as &$reservation) {
                //Take only the date part
                $res_date = date("Y-m-d", strtotime($reservation->res_date));

                //Check if it matches the current date
                if ($res_date == $dateStr) {
                    //Add this reservation to the dates array
                    array_push($dateReservations, $reservation);
                }
            }

            $date["reservations"] = $dateReservations;
        }
    }

    function loadReservationStatus($res)
    {
        $res->status_desc = null;
        $res->status_color = "#000000";
        $res->status_weight = "bold";
        $res->is_arrived_btn_class = null;
        $res->is_arrived_btn_disabled = 0;
        $res->is_arrived_btn_label = null;
        $res->sti_btn_class = null;
        $res->sti_btn_disabled = 0;
        $res->sti_btn_label = null;
        $res->screened_btn_class = null;
        $res->screened_btn_disabled = 0;
        $res->screened_btn_label = null;
        $res->confirmed_btn_class = null;
        $res->confirmed_btn_disabled = 0;
        $res->confirmed_btn_label = null;

        if ($res->is_arrived == 0) {
            $res->status_desc = Lang::get("reservations/res.status_not_arrived");
            $res->status_weight = "normal";
        } else if ($res->screened_status == ORAConsts::RES_SCREENED_STATUS_EMPTY) {
            $res->status_desc = Lang::get("reservations/res.status_not_screened");
            $res->status_color = "#FF0000";
            $res->status_weight = "normal";
        } else if ($res->screened_status != ORAConsts::RES_SCREENED_STATUS_POSITIVE) {
            $res->status_desc = Lang::get("reservations/res.status_done");
        } else if ($res->confirmed_status == ORAConsts::RES_CONFIRMED_STATUS_EMPTY) {
            $res->status_desc = Lang::get("reservations/res.status_not_confirmed");
            $res->status_color = "#0000FF";
            $res->status_weight = "normal";
        } else {
            $res->status_desc = Lang::get("reservations/res.status_done");
        }

        $res->is_arrived_btn_class = $res->is_arrived == 1 ? "success" : "danger";
        $res->is_arrived_btn_disabled = ($res->sti_status != ORAConsts::RES_STI_STATUS_EMPTY || $res->screened_status != ORAConsts::RES_SCREENED_STATUS_EMPTY) ? 1 : 0;
        $res->is_arrived_btn_label = $res->is_arrived == 1 ? Lang::get('reservations/res.arrived') : Lang::get('reservations/res.not_arrived');

        $res->sti_btn_class = $res->sti_status != ORAConsts::RES_STI_STATUS_EMPTY ? "success" : "danger";
        $res->sti_btn_disabled = ($res->is_arrived == 0);
        $res->sti_btn_label = $res->sti_status != ORAConsts::RES_STI_STATUS_EMPTY ? Lang::get('reservations/res.sti') : Lang::get('reservations/res.not_sti');

        $res->screened_btn_class = $res->screened_status != ORAConsts::RES_SCREENED_STATUS_EMPTY ? "success" : "danger";
        $res->screened_btn_disabled = ($res->is_arrived == 0 || $res->confirmed_status != ORAConsts::RES_CONFIRMED_STATUS_EMPTY) ? 1 : 0;
        $res->screened_btn_label = $res->screened_status != ORAConsts::RES_SCREENED_STATUS_EMPTY ? Lang::get('reservations/res.screened_status_yes') : Lang::get('reservations/res.screened_status_no');

        $res->confirmed_btn_class = $res->confirmed_status != ORAConsts::RES_CONFIRMED_STATUS_EMPTY ? "success" : "danger";
        $res->confirmed_btn_disabled = ($res->screened_status == ORAConsts::RES_SCREENED_STATUS_EMPTY || $res->screened_status != ORAConsts::RES_SCREENED_STATUS_POSITIVE) ? 1 : 0;
        $res->confirmed_btn_label = $res->confirmed_status != ORAConsts::RES_CONFIRMED_STATUS_EMPTY ? Lang::get('reservations/res.confirmed') : Lang::get('reservations/res.not_confirmed');

        //var_dump($res);
        //die;
    }

    public function getDates($clinicId, &$dateStart, &$dateEnd, $dateAction)
    {
        $this->setLocalTimezone();

        //Calculate date start
        if ($dateStart == null) {
            //Start today
            $dateStart = new \DateTime("now", $this->getLocalTimezone());
        }
        $dateStart->setTime(0, 0, 0); //Move to start of day

        if ($dateAction == 'P') {
            //Move to previous range
            $dateStart->sub(new \DateInterval('P15D'));
        } else if ($dateAction == 'N') {
            //Move to next range
            $dateStart->add(new \DateInterval('P15D'));
        }

        //Calculate the date end
        $dateEnd = clone $dateStart;
        $dateEnd->add(new \DateInterval('P14D')); //Add X days to the current datatime
        $dateEnd->setTime(23, 59, 59); //Move to the end of day

        //Select the clinic's holidays
        $holidays = DB::table('clinics_holidays')
            ->where('clinic_id', '=', $clinicId)
            ->get();

        $dates = array();
        $date = clone $dateStart;

        //Begin the loop to check the available slots
        while ($date <= $dateEnd) {
            //Check if it's a holiday
            $is_holiday = $this->isHoliday($date, $holidays);

            array_push($dates, array(
                "date" => clone $date,
                "display_date" => $this->getDisplayDate($date),
                "is_holiday" => $is_holiday,
                "reservations" => array()));

            //Move to the next date
            $date->modify('+1days');
        }

        return $dates;
    }

    function getDisplayDate($date)
    {
        $dayOfWeek = $date->format("w");
        $month = $date->format("n");
        $dayOfMonth = $date->format("d");

        $display = Lang::get("dates.d" . $dayOfWeek);
        $display .= ", ";
        $display .= $dayOfMonth;
        $display .= " ";
        $display .= Lang::get("dates.m" . $month);

        return $display;
    }

    function isHoliday($date, $holidays)
    {
        //Take only the date part
        $dateStr = $date->format("Y-m-d");
        $monthDayStr = $date->format("m-d");

        //Search through the holidays
        foreach ($holidays as $holiday) {
            if ($holiday->is_recurring) {
                $md = date("m-d", strtotime($holiday->holiday_date));
                //Check the month/day only, ignore the year
                if ($monthDayStr == $md) {
                    return true;
                }
            } else {
                //Check the precise date
                if ($dateStr == $holiday->holiday_date) {
                    return true;
                }
            }
        }

        return false;
    }

    public function updateSlot(Request $request)
    {
        $this->setLocalTimezone();

        if(!is_numeric ($request->get('res_phone'))) {
            throw new \Exception(Lang::get("reservations/res.err_phone_numeric"));
        }

        $res = Reservation::findOrFail($request->get('res_id'));
        $res->res_date = $request->get('res_date');
        $res->client_phone_num = $request->get('res_phone');

        $res->save();

        return $res;
    }

    public function update(Request $request)
    {
        $this->setLocalTimezone();

        $res = Reservation::findOrFail($request->get('pk'));
        $name = $request->get('name');
        $value = $request->get('value');
        $res->$name = $value;

        if ($name == "confirmed_status") {
            if ($value != ORAConsts::RES_CONFIRMED_STATUS_EMPTY) $res->confirmed_status_date = date('Y-m-d H:i');
            else $res->confirmed_status_date = null;
        } else if ($name == "sti_status") {
            if ($value != ORAConsts::RES_STI_STATUS_EMPTY) $res->sti_status_date = date('Y-m-d H:i');
            else $res->sti_status_date = null;
        } else if ($name == "screened_status") {
            if ($value != ORAConsts::RES_SCREENED_STATUS_EMPTY) $res->screened_status_date = date('Y-m-d H:i');
            else $res->screened_status_date = null;
        } else if ($name == "is_arrived") {
            if ($value == "1") $res->arrived_date = date('Y-m-d H:i');
            else $res->arrived_date = null;
        }

        $res->save();

        //Add fields
        $this->loadReservationStatus($res);

        //Send SMS to the user when "is arrived" is checked
        if ($name == "is_arrived" && $value == "1") {
            $this->sendArrivedSMS($res);
        }

        return $res;
    }

    function sendArrivedSMS($reservation)
    {
        $phoneNum = "";
        $numbers = array("");
        $sender = "";
        $isTest = true;
        $message = "";

        try {
            $this->setLocalTimezone();

            //Load the clinic
            $clinic = Clinic::getById($reservation->clinic_id);

            //Read and cleanup the phone number
            $phoneNum = $reservation->client_phone_num;
            $phoneNum = str_replace(" ", "", $phoneNum);
            $phoneNum = str_replace("+", "", $phoneNum);

            //TEST:
            //$phoneNum = "9820052135";

            //Prepare the SMS body
            $date = date_format(date_create($reservation->res_date), "d/m");
            $time = date_format(date_create($reservation->res_date), "H:i");

            $smsBody = Lang::get('reservations/res.sms_res_arrived_body');
            $smsBody = str_replace("{d}", $date, $smsBody);
            $smsBody = str_replace("{t}", $time, $smsBody);
            $smsBody = str_replace("{c}", $clinic->name, $smsBody);
            $smsBody = str_replace("{l}", $clinic->location_desc, $smsBody);
            $smsBody = str_replace("{a}", $clinic->address_desc, $smsBody);

            //Cleanup dashes from the body
            $smsBody = str_replace('–', '-', $smsBody); // endash
            $smsBody = str_replace('—', '-', $smsBody); // endash
            $smsBody = str_replace(' ', ' ', $smsBody); // replace weird character which causes errors

            $apiKey = env('SMS_API_KEY');
            $textlocal = new Textlocal(false, false, $apiKey);
            $numbers = array($phoneNum);
            $sender = env('SMS_SENDER');
            $message = $smsBody;
            $isTest = env('SMS_TEST');

            //First check if we are in the allowed time frame to send SMS
            $currentTime = \DateTime::createFromFormat('H:i', date('H:i'));
            $timeStart = \DateTime::createFromFormat('H:i', env('SMS_TIME_START'));
            $timeEnd = \DateTime::createFromFormat('H:i', env('SMS_TIME_END'));

            if ($currentTime >= $timeStart && $currentTime <= $timeEnd) {

                //Try to send the SMS
                $result = $textlocal->sendSms($numbers, $message, $sender, null, $isTest);
                if ($result->status != "success") {
                    throw new \Exception("Unexpected result: " . $result->status);
                }

            } else {
                //We are not in the correct time frame. Store the SMS log record, it will be sent by the scheduled job.
                $this->recordSmsLog($reservation->id, $numbers, $message, $sender, $isTest, 'Current time not in allowed time frame.');
            }

        } catch (\Exception $e) {
            Log::error("Error sending reservation SMS to " . $phoneNum . " - Error: " . $e->getMessage());

            //Store SMS in log table, will retry with a scheduled job
            $this->recordSmsLog($reservation->id, $numbers, $message, $sender, $isTest, $e->getMessage());
        }
    }

    function recordSmsLog($reservationId, $numbers, $message, $sender, $isTest, $errorMessage)
    {
        try {
            $recipient = implode(",", $numbers);
            if ($recipient != "") {
                DB::table('sms_logs')->insert(
                    array(
                        'reservation_id' => $reservationId,
                        'sms_number' => $recipient,
                        'sms_message' => $message,
                        'sms_sender' => $sender,
                        'sms_test' => ($isTest == true ? 1 : 0),
                        'status' => ORAConsts::SMS_STATUS_RETRY,
                        'retry_count' => 0,
                        'error_message' => $errorMessage,
                        'created_at' => DB::raw('now()'),
                        'last_try_at' => null,
                    )
                );
            }
        } catch (\Exception $e) {
            Log::error("Error inserting SMS Log - Error: " . $e->getMessage());
        }
    }

    public function qrCode($code)
    {
        return QrCode::margin(1)->size(150)->generate($code);
    }

    function isWorkingTime($date, $workingTimes)
    {
        //Take only the hour/minutes part
        $timeToCheck = strtotime($date->format("H:i"));
        $dayOfWeek = $date->format("w");

        //Search through the working times
        foreach ($workingTimes as $workingTime) {
            //First must match the day of week
            if ($workingTime->day_num == $dayOfWeek) {
                //Then can check the time start/end
                if ($workingTime->time_start_1 != '' && $workingTime->time_end_1 != '') {
                    $timeStart1 = strtotime($workingTime->time_start_1);
                    $timeEnd1 = strtotime($workingTime->time_end_1);
                    if ($timeToCheck >= $timeStart1 && $timeToCheck < $timeEnd1) {
                        return true;
                    }
                }

                if ($workingTime->time_start_2 != '' && $workingTime->time_end_2 != '') {
                    $timeStart2 = strtotime($workingTime->time_start_2);
                    $timeEnd2 = strtotime($workingTime->time_end_2);
                    if ($timeToCheck >= $timeStart2 && $timeToCheck < $timeEnd2) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    function isReserved($date, $reservations)
    {
        //Remove the seconds part
        $dateStr = $date->format("Y-m-d H:i");

        //Search through the working times
        foreach ($reservations as $reservation) {
            //Remove the seconds part
            $res_date = date("Y-m-d H:i", strtotime($reservation->res_date));
            if ($dateStr == $res_date) {
                return true;
            }
        }

        return false;
    }

    public function loadAvailableSlots($clinicId, $date, $currentSlot)
    {
        $this->setLocalTimezone();

        //Select the clinic information
        $clinic = DB::table('clinics')
            ->where('id', '=', $clinicId)
            ->first();

        //Select the clinic working times
        $workingTimes = DB::table('clinics_work_times')
            ->where('clinic_id', '=', $clinicId)
            ->get();

        $slotLength = $clinic->res_time_slot_length;
        if ($slotLength <= 5) $slotLength = 5; //Minimum time slot length

        $dateStart = date_create($date);
        //Calculate the first slot time
        $minutes = $dateStart->format("i");
        $minutes += ($slotLength - ($minutes % $slotLength));
        $dateStart->setTime($dateStart->format("H"), $minutes, 0); //Move to the first slot time
        //Calculate the date end
        $dateEnd = clone $dateStart;
        $dateEnd->setTime(23, 59, 59); //Move to the end of day

        //Select the reservations within this time range
        $reservations = DB::table('reservations')
            ->where('clinic_id', '=', $clinicId)
            ->where('res_date', '>=', $dateStart->format('Y-m-d H:i:s'))
            ->where('res_date', '<=', $dateEnd->format('Y-m-d H:i:s'))
            ->whereNull('deleted_at')
            ->get();

        //Select the clinic's holidays
        $holidays = DB::table('clinics_holidays')
            ->where('clinic_id', '=', $clinicId)
            ->get();

        $slots = array();

        //Add current item, as the first of the combo
        if($currentSlot != '' && $currentSlot != '-1') {
            array_push($slots, array("date" => $currentSlot));
        }

        //Begin the loop to check the available slots
        while ($dateStart <= $dateEnd) {
            //Check if it's a holiday
            if (!$this->isHoliday($dateStart, $holidays)) {
                //Check if it's working time for the clinic
                if ($this->isWorkingTime($dateStart, $workingTimes)) {
                    //Check the slot is not already reserved
                    if (!$this->isReserved($dateStart, $reservations)) {
                        //OK, This slot is available, add it to the return array
                        $slotDate = clone $dateStart;
                        array_push($slots, $slotDate);
                    }
                }
            }

            //Move to the next slot
            $dateStart->modify('+' . $slotLength . 'minutes');
        }

        return json_encode($slots);
    }

    public function delete($id)
    {
        $this->setLocalTimezone();
        $res = Reservation::findOrFail($id);
        $res->deleted_at = date("Y-m-d H:i:s");
        $res->save();
    }

    function translateCode($value, $values)
    {
        if (!is_null($value)) {
            if ($value < count($values)) {
                return $values[$value];
            }
            return $value;
        }
        return '';
    }

    function sendLinkBySms(Request $request)
    {
        $resId = $request->get('resId');
        $res = Reservation::findOrFail($resId);
        $this->sendLinkSMS($res);
    }

    function sendLinkSMS($reservation)
    {
        $phoneNum = "";
        $numbers = array("");
        $sender = "";
        $isTest = true;
        $message = "";

        try {
            $this->setLocalTimezone();

            //Read and cleanup the phone number
            $phoneNum = $reservation->client_phone_num;
            $phoneNum = str_replace(" ", "", $phoneNum);
            $phoneNum = str_replace("+", "", $phoneNum);

            //Prepare the SMS body
            $linkUrl = ORAHelper::getIndexUrl($reservation->res_code_long);
            $smsBody = Lang::get('reservations/res.sms_link_body');
            $smsBody = str_replace("{l}", $linkUrl, $smsBody);

            //Cleanup dashes from the body
            $smsBody = str_replace('–', '-', $smsBody); // endash
            $smsBody = str_replace('—', '-', $smsBody); // endash
            $smsBody = str_replace(' ', ' ', $smsBody); // replace weird character which causes errors

            $apiKey = env('SMS_API_KEY');
            $textlocal = new Textlocal(false, false, $apiKey);
            $numbers = array($phoneNum);
            $sender = env('SMS_SENDER');
            $message = $smsBody;
            $isTest = env('SMS_TEST');

            //First check if we are in the allowed time frame to send SMS
            $currentTime = \DateTime::createFromFormat('H:i', date('H:i'));
            $timeStart = \DateTime::createFromFormat('H:i', env('SMS_TIME_START'));
            $timeEnd = \DateTime::createFromFormat('H:i', env('SMS_TIME_END'));

            if ($currentTime >= $timeStart && $currentTime <= $timeEnd) {

                //Try to send the SMS
                $result = $textlocal->sendSms($numbers, $message, $sender, null, $isTest);
                if ($result->status != "success") {
                    throw new \Exception("Unexpected result: " . $result->status);
                }

            } else {
                //We are not in the correct time frame. Store the SMS log record, it will be sent by the scheduled job.
                $this->recordSmsLog($reservation->id, $numbers, $message, $sender, $isTest, 'Current time not in allowed time frame.');
            }

        } catch (\Exception $e) {
            Log::error("Error sending link SMS to " . $phoneNum . " - Error: " . $e->getMessage());

            //Store SMS in log table, will retry with a scheduled job
            $this->recordSmsLog($reservation->id, $numbers, $message, $sender, $isTest, $e->getMessage());
        }
    }

    function testSmsSend(){

        $smsBody = "Test youtube https://youtu.be/dWcu_L1Ov3o";
        //$phoneNum = "8452030628";
        $phoneNum = "9820052135";

        $smsBody = str_replace('-', ' ', $smsBody); // endash
        $smsBody = str_replace('—', '-', $smsBody); // endash
        $smsBody = str_replace(' ', ' ', $smsBody); // replace weird character which causes errors


        $apiKey = env('SMS_API_KEY');
        $textlocal = new Textlocal(false, false, $apiKey);
        $numbers = array($phoneNum);
        $sender = env('SMS_SENDER');
        $message = $smsBody;
        $isTest = env('SMS_TEST');

        echo ($smsBody);

        $result = $textlocal->sendSms($numbers, $message, $sender, null, $isTest);


        var_dump($result);
        die;
    }
}
