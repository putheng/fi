<?php

namespace App\Http\Controllers;

use App\Http\Common\ORAConsts;
use App\Http\Common\ORAHelper;
use App\Models\Clinic;
use App\Models\Form_A1;
use App\Models\Form_A2;
use App\Models\Form_A3;
use App\Models\Form_A4;
use App\Models\Form_A5;
use App\Models\Form_B1;
use App\Models\Form_B2;
use App\Models\Form_C1;
use App\Models\Form_D1;
use App\Models\Reservation;
use Box\Spout\Writer\Style\StyleBuilder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Sentinel;
use App\Models\OrganizationsGroups;
use App\Models\Organization;
use ied3vil\LanguageSwitcher\Facades\LanguageSwitcher;


class ExportDataController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission_user');
    }

    public function index($clinicId = 0)
    {
        $user = Sentinel::getUser();
        if ($this->isAdminAny($user)) {
            //Prepare data for the combobox
            $clinics = Clinic::getEnabledCombo(true);
            $clinicId = -1; //Select single sheet by default
        } else if ($this->isMultiClinicUser($user)) {
            //Only the clinics assigned to the user
            $usersClinics = $user->usersClinics()->pluck('clinic_id');
            $clinics = Clinic::getUserClinicsCombo($usersClinics, true);
            $clinicId = -1; //Select single sheet by default
        } else {
            //Only the clinic assigned to the user is visible, no matter what is passed
            $clinicId = $user->clinic_id;
            $clinics = [];
        }

        $dateStart = null;
        $dateEnd = null;

        return view('admin.export.index', compact('clinics'))
            ->with('dateStart', $dateStart)
            ->with('dateEnd', $dateEnd)
            ->with('clinicId', $clinicId);
    }

    public function getColumnHeaders($user, $includeAssessmentFields)
    {
        //Load table columns
        $headers = [
            'res_date_d', 'res_date_t', 'res_date_d_raw', 'clinic_code', 'res_created_date', 'client_name',
            'res_code_long', 'res_code_short', 'client_age', 'previously_reached_rep_period', 'client_satisfaction', 'client_comments', 'gps_lat', 'gps_long',
            'services', 'token_num', 'token_title', 'token_purpose', 'is_incentive', 'client_phone_num', 'is_arrived', 'arrived_date'];

        //Don't add some columns for limited admin
        if (!$this->isLimitedAdmin($user)) {
            array_push($headers,  'sti_status', 'sti_status_date',  'screened_status', 'screened_status_date', 'confirmed_status', 'confirmed_status_date', 'status_desc');
        }

        array_push($headers, 'clinic_internal_code', 'clinic_notes', 'res_url_param_r', 'index_url', 'res_url_param');

        if ($includeAssessmentFields) {
            array_push($headers, 'res_assessment_date');
            array_push($headers, 'res_is_msm', 'res_is_sw', 'res_is_pwid', 'res_is_tg', 'res_is_indeterminate', 'res_is_kp', 'res_risks_count', 'res_test_recent');
        }

        array_push($headers, 'res_risk_result', 'res_kp_labels');

        if ($includeAssessmentFields) {
            array_push($headers, 'last_test', 'hiv_status', 'treatment_history', 'gender', 'sex_at_birth');

            $sex_with_count = 5;
            for ($i = 0; $i < $sex_with_count; $i++) {
                array_push($headers, 'sex_with_' . $i);
            }
            $risks_count = 11;
            for ($i = 0; $i < $risks_count; $i++) {
                array_push($headers, 'risks_' . $i);
            }
        }

        foreach ($headers as &$header) {
            $header = Lang::get("export/title." . $header);
        }

        return $headers;
    }

    function validateDate($date, $time)
    {
        $d = \DateTime::createFromFormat('d/m/Y', $date);
        if ($d && $d->format('d/m/Y') === $date) {
            return $d->format('Y-m-d') . $time; //return MySql format
        }
        return '';
    }

//    public function fillWithServices($rows, $dateFrom, $dateTo, $clinic)
//    {
//        // Get the services
//        $sql = DB::table("reservations_services AS rs")
//            ->join('reservations AS r', 'rs.reservation_id', '=', 'r.id')
//            ->join('clinics_services AS cs', 'rs.service_id', '=', 'cs.id')
//            ->select('rs.*', 'cs.service_desc_lang1', 'cs.service_desc_lang2')
//            ->where('r.clinic_id', '=', $clinic)
//            ->whereNull('r.deleted_at');
//
//        if ($dateFrom != '')
//            $sql->where('r.res_date', '>=', $dateFrom);
//
//        if ($dateTo != '')
//            $sql->where('r.res_date', '<=', $dateTo);
//
//        $services = $sql
//            ->distinct()
//            ->get()
//            ->toArray();
//
//        // Assign the service to the reservation
//        if ($services != null && count($services) > 0) {
//            foreach ($rows as &$row) {
//                // Filter the services
//                $filtered = array_filter($services, function ($element) use ($row) {
//                    return $element->reservation_id === $row->id;
//                });
//
//                // Check
//                if ($filtered != null && count($filtered) > 0) {
//                    // Extract the language column
//                    $column = LanguageSwitcher::getCurrentLanguage() == ORAConsts::LANGUAGE2 ? "service_desc_lang2" : "service_desc_lang1";
//                    $language = array_column($filtered, $column);
//
//                    // Join
//                    $row->services = implode(", ", $language);
//                }
//            }
//        }
//    }

    public function export(Request $request)
    {
        try {
            //First validate the user input
            $user = Sentinel::getUser();
            $isSingleSheet = false;

            if ($this->isAdminAny($user)) {
                //Only the admin can pick a clinic
                $clinicId = $request->input('clinic_id');
                if ($clinicId == 0 || $clinicId == -1) {
                    //Select all clinics, even disabled
                    $query = DB::table("clinics")
                        ->whereNull('deleted_at');

                    //User limited to one site id
                    if ($user->site_id != null && $user->site_id != 0) {
                        $query = $query->where('site_id', $user->site_id);
                    }

                    $clinics = $query->get();

                    if ($clinicId == -1) {
                        $isSingleSheet = true;
                    }
                } else {
                    //Select the chosen clinic
                    $clinics = DB::table("clinics")
                        ->where('id', '=', $clinicId)
                        ->whereNull('deleted_at')
                        ->get();
                }
            } else if ($this->isMultiClinicUser($user)) {

                //Only the admin can pick a clinic
                $clinicId = $request->input('clinic_id');

                //Only the clinics assigned to the user
                $usersClinics = $user->usersClinics()->pluck('clinic_id');

                if ($clinicId == 0 || $clinicId == -1) {
                    //Select all clinics, even disabled
                    $clinics = DB::table("clinics")
                        ->whereIn('id', $usersClinics)
                        ->whereNull('deleted_at')
                        ->get();

                    if ($clinicId == -1) {
                        $isSingleSheet = true;
                    }
                } else {

                    //Make sure the clinic is available to the user (could hack a different clinic code)
                    if (!in_array($clinicId, $usersClinics->toArray())) {
                        $clinicId = $usersClinics[0];
                    }

                    //Select the chosen clinic
                    $clinics = DB::table("clinics")
                        ->where('id', '=', $clinicId)
                        ->whereNull('deleted_at')
                        ->get();
                }
            } else {
                //This user can export only his own clinic
                $clinicId = $user->clinic_id;
                $clinics = DB::table("clinics")
                    ->where('id', '=', $clinicId)
                    ->whereNull('deleted_at')
                    ->get();
            }

            //Validate input dates and convert to MySql format
            $dateFrom = $this->validateDate($request->input('date_from'), ' 00:00:00');
            $dateTo = $this->validateDate($request->input('date_to'), ' 25:59:59');

            //Additional options
            $includeAssessmentFields = ($request->input('include_ra') == "1");
            $includeAssessmentRecords = ($request->input('include_assessment') == "1");

            // Response settings
            ini_set('max_execution_time', 3600); // Set max execution to 1 hour
            ini_set('memory_limit', '128M'); // Set max memory

            // Style settings
            $style = (new StyleBuilder())
                ->setFontSize(10)
                ->setShouldWrapText(false)
                ->build();

            $headerStyle = (new StyleBuilder())
                ->setFontBold()
                ->setFontSize(10)
                ->setShouldWrapText(false)
                ->build();

            // Define the writer and the stream
            $this->setLocalTimezone();
            $writer = WriterFactory::create(Type::XLSX);
            //$writer = WriterFactory::create(Type::CSV);

            //SET STORAGE TEMP DIRECTORY (needed for BigRock hosting)
            $tempPath = storage_path() . "/temp";
            if (!file_exists($tempPath)) mkdir($tempPath, 0755);
            $writer->setTempFolder($tempPath);

            //TEST
            $writer->openToBrowser("ReservationsExport_" . date("Ymd_Hi") . ".xlsx");
            //$writer->openToFile("D:/ReservationsExport_" . date("Ymd_Hi") . ".xlsx");
            //$writer->openToBrowser("D:/ReservationsExport_" . date("Ymd_Hi") . ".csv");

            if ($isSingleSheet) {
                $sheet = $writer->getCurrentSheet();
                $sheet->setName("ALL_CLINICS");
                $headers = $this->getColumnHeaders($user, $includeAssessmentFields);
                $writer->addRowWithStyle($headers, $headerStyle);
            }

            // Cycle all clinics
            foreach ($clinics as $index => $clinic) {

                if (!$isSingleSheet) {
                    // Check for header
                    if ($index != 0) {
                        $writer->addNewSheetAndMakeItCurrent();
                    }

                    $sheet = $writer->getCurrentSheet();
                    $sheet->setName($this->clearSpecialChars($clinic->code));

                    // Get the columns information and draw the header
                    $headers = $this->getColumnHeaders($user, $includeAssessmentFields);
                    $writer->addRowWithStyle($headers, $headerStyle);
                }

                $query = DB::table("reservations AS r")
                    ->leftJoin('clinics AS c', 'r.clinic_id', '=', 'c.id')
                    ->leftJoin('tokens AS t', 'r.token_id', '=', 't.id')
                    ->leftJoin('reservations_assessments AS ra', 'r.id', '=', 'ra.reservation_id')
                    ->where('r.clinic_id', $clinic->id)
                    ->where('r.res_status', ORAConsts::RES_STATUS_RESERVATION)
                    ->whereNull("r.deleted_at");

                if ($dateFrom != '') {
                    $query = $query->where('res_date', '>=', $dateFrom);
                }
                if ($dateTo != '') {
                    $query = $query->where('res_date', '<=', $dateTo);
                }

                $query = $query
                    ->orderBy('r.res_date')
                    ->select(
                        'r.id',
                        DB::raw("DATE_FORMAT(r.res_date, '%Y-%m-%d') as res_date_d"),
                        DB::raw("DATE_FORMAT(r.res_date, '%T') as res_date_t"),
                        DB::raw("DATE_FORMAT(r.res_date, '%Y-%m-%d %T') as res_date_d_raw"),
                        'c.code AS clinic_code',
                        DB::raw("DATE_FORMAT(r.res_created_date, '%Y-%m-%d %T') as res_created_date"),
                        'r.client_name',
                        'r.res_code_long',
                        'r.res_code_short',
                        'r.client_age',
                        'r.previously_reached_rep_period',
                        'r.client_satisfaction',
                        'r.client_comments',
                        'r.gps_lat',
                        'r.gps_long',
                        DB::raw("'' as services"),
                        't.token_num AS token_num',
                        't.title AS token_title',
                        't.purpose_desc AS token_purpose',
                        DB::raw("if(t.is_incentive = 1, 1, NULL) as is_incentive"),
                        'r.client_phone_num',
                        DB::raw("if(r.is_arrived   = 1, 1, NULL) as is_arrived"),
                        DB::raw("DATE_FORMAT(r.arrived_date, '%Y-%m-%d %T') as arrived_date"),
                        DB::raw("if(r.sti_status = " . ORAConsts::RES_STI_STATUS_EMPTY . ", NULL, r.sti_status) as sti_status"),
                        DB::raw("DATE_FORMAT(r.sti_status_date, '%Y-%m-%d %T') as sti_status_date"),
                        DB::raw("if(r.screened_status = " . ORAConsts::RES_SCREENED_STATUS_EMPTY . ", NULL, r.screened_status) as screened_status"),
                        DB::raw("DATE_FORMAT(r.screened_status_date, '%Y-%m-%d %T') as screened_status_date"),
                        DB::raw("if(r.confirmed_status = " . ORAConsts::RES_CONFIRMED_STATUS_EMPTY . ", NULL, r.confirmed_status) as confirmed_status"),
                        DB::raw("DATE_FORMAT(r.confirmed_status_date, '%Y-%m-%d %T') as confirmed_status_date"),
                        DB::raw("'' as status_desc"),
                        'r.clinic_internal_code',
                        'r.clinic_notes',
                        'r.res_url_param_r',
                        DB::raw("'' as index_url"),
                        'r.res_url_param',
                        DB::raw("DATE_FORMAT(r.res_assessment_date, '%Y-%m-%d %T') as res_assessment_date"),
                        'ra.res_is_msm',
                        'ra.res_is_sw',
                        'ra.res_is_pwid',
                        'ra.res_is_tg',
                        'ra.res_is_indeterminate',
                        'ra.res_is_kp',
                        'ra.res_risks_count',
                        'ra.res_test_recent',
                        'r.res_risk_result',
                        'r.res_kp_labels',
                        'ra.last_test',
                        'ra.hiv_status',
                        'ra.treatment_history',
                        'ra.gender',
                        'ra.sex_at_birth',
                        'ra.sex_with',
                        'ra.risks'
                    );

                //TEST RESULTS:
//                try {
//                    $res = $query->get();
//                    //var_dump($res);
//                } catch (\Exception $e) {
//                    var_dump($e);
//                }

                $query->chunk(500, function ($rows) use ($user, $writer, $style, $dateFrom, $dateTo, $clinic, $includeAssessmentFields) {

                    // Services removed for India
                    // Apply the services
                    //$this->fillWithServices($rows, $dateFrom, $dateTo, $clinic->id);

                    //Convert the results into an array of arrays
                    $rows = collect($rows)->map(function ($x) use ($user, $includeAssessmentFields) {
                        unset($x->id);

                        $x->res_risk_result = $this->translateRiskResult($x->res_risk_result);
                        $x->index_url = ORAHelper::getIndexUrl($x->res_code_long);

                        if (!$includeAssessmentFields) {
                            unset($x->res_assessment_date);
                            unset($x->res_is_msm);
                            unset($x->res_is_sw);
                            unset($x->res_is_pwid);
                            unset($x->res_is_tg);
                            unset($x->res_is_indeterminate);
                            unset($x->res_is_kp);
                            unset($x->res_risks_count);
                            unset($x->res_test_recent);
                            unset($x->last_test);
                            unset($x->hiv_status);
                            unset($x->treatment_history);
                            unset($x->gender);
                            unset($x->sex_at_birth);
                            unset($x->sex_with);
                            unset($x->risks);
                        } else {
                            $x->hiv_status = $this->translateCode($x->hiv_status, array('negative', 'positive', 'not_sure', 'not_say'));
                            $x->treatment_history = $this->translateCode($x->treatment_history, array('always_take', 'not_always_take', 'not_yet_treatment', 'not_taking_arv'));
                            $x->gender = $this->translateCode($x->gender, array('male', 'female', 'tg', 'complicated', 'not_say'));
                            $x->sex_at_birth = $this->translateCode($x->sex_at_birth, array('male', 'female', 'not_say'));
                            $x->last_test = $this->translateCode($x->last_test, array('less_6mo', 'more_6mo', 'never'));
                        }

                        //Remove fields for limited visibility admin
                        if ($this->isLimitedAdmin($user)) {
                            unset($x->sti_status);
                            unset($x->sti_status_date);
                            unset($x->screened_status);
                            unset($x->screened_status_date);
                            unset($x->confirmed_status);
                            unset($x->confirmed_status_date);
                            unset($x->status_desc);
                        } else {
                            //Prepare status description
                            if (is_null($x->is_arrived)) {
                                $x->status_desc = Lang::get("reservations/res.status_not_arrived");
                            } else if (is_null($x->screened_status)) {
                                $x->status_desc = Lang::get("reservations/res.status_not_screened");
                            } else if ($x->screened_status != ORAConsts::RES_SCREENED_STATUS_POSITIVE) {
                                $x->status_desc = Lang::get("reservations/res.status_done");
                            } else if (is_null($x->confirmed_status)) {
                                $x->status_desc = Lang::get("reservations/res.status_not_confirmed");
                            } else {
                                $x->status_desc = Lang::get("reservations/res.status_done");
                            }

                            //Set sti status desc (NOTE: PHP threat zero same as empty, that's why the weird IF)
                            if($x->sti_status != '' || $x->sti_status === 0) {
                                $x->sti_status = Lang::get("reservations/res.sti_status_" . $x->sti_status);
                            }
                        }

                        $arr = (array)$x;

                        if ($includeAssessmentFields) {
                            $this->explodeColumns($arr);
                        }

                        return $arr;

                    })->toArray();

                    // Write a new row
                    $writer->addRowsWithStyle($rows, $style);
                });
            }

            //Assessment records - They are not attached to any clinic
            if ($includeAssessmentRecords) {

                //Create a sheet for them, or append to the single sheet
                if (!$isSingleSheet) {
                    $writer->addNewSheetAndMakeItCurrent();
                    $sheet = $writer->getCurrentSheet();
                    $sheet->setName("NO CLINIC");

                    // Get the columns information and draw the header
                    $headers = $this->getColumnHeaders($user, $includeAssessmentFields);
                    $writer->addRowWithStyle($headers, $headerStyle);
                }

                $query = DB::table("reservations AS r")
                    ->leftJoin('tokens AS t', 'r.token_id', '=', 't.id')
                    ->leftJoin('reservations_assessments AS ra', 'r.id', '=', 'ra.reservation_id')
                    ->where('r.res_status', ORAConsts::RES_STATUS_ASSESSMENT)
                    ->whereNull("r.deleted_at")
                    ->orderBy('r.created_at')
                    ->select(
                        'r.id',
                        DB::raw("'' res_date_d"),
                        DB::raw("'' as res_date_t"),
                        DB::raw("'' as res_date_d_raw"),
                        DB::raw("'' as clinic_code"),
                        DB::raw("'' as res_created_date"),
                        DB::raw("'' as client_name"),
                        DB::raw("'' as res_code_long"),
                        DB::raw("'' as res_code_short"),
                        DB::raw("'' as client_age"),
                        DB::raw("'' as previously_reached_rep_period"),
                        DB::raw("'' as client_satisfaction"),
                        DB::raw("'' as client_comments"),
                        DB::raw("'' as gps_lat"),
                        DB::raw("'' as gps_long"),
                        DB::raw("'' as services"),
                        't.token_num AS token_num',
                        't.title AS token_title',
                        't.purpose_desc AS token_purpose',
                        DB::raw("if(t.is_incentive = 1, 1, NULL) as is_incentive"),
                        DB::raw("'' as client_phone_num"),
                        DB::raw("'' as is_arrived"),
                        DB::raw("'' as arrived_date"),
                        DB::raw("'' as sti_status"),
                        DB::raw("'' as sti_status_date"),
                        DB::raw("'' as screened_status"),
                        DB::raw("'' as screened_status_date"),
                        DB::raw("'' as confirmed_status"),
                        DB::raw("'' as confirmed_status_date"),
                        DB::raw("'' as status_desc"),
                        DB::raw("'' as clinic_internal_code"),
                        DB::raw("'' as clinic_notes"),
                        'r.res_url_param_r',
                        DB::raw("'' as index_url"),
                        'r.res_url_param',
                        DB::raw("DATE_FORMAT(r.res_assessment_date, '%Y-%m-%d %T') as res_assessment_date"),
                        'ra.res_is_msm',
                        'ra.res_is_sw',
                        'ra.res_is_pwid',
                        'ra.res_is_tg',
                        'ra.res_is_indeterminate',
                        'ra.res_is_kp',
                        'ra.res_risks_count',
                        'ra.res_test_recent',
                        'r.res_risk_result',
                        'r.res_kp_labels',
                        'ra.last_test',
                        'ra.hiv_status',
                        'ra.treatment_history',
                        'ra.gender',
                        'ra.sex_at_birth',
                        'ra.sex_with',
                        'ra.risks'
                    );

                $query->chunk(500, function ($rows) use ($user, $writer, $style, $includeAssessmentFields) {
                    //Convert the results into an array of arrays
                    $rows = collect($rows)->map(function ($x) use ($user, $includeAssessmentFields) {
                        unset($x->id);

                        $x->res_risk_result = $this->translateRiskResult($x->res_risk_result);

                        if (!$includeAssessmentFields) {
                            unset($x->res_assessment_date);
                            unset($x->res_is_msm);
                            unset($x->res_is_sw);
                            unset($x->res_is_pwid);
                            unset($x->res_is_tg);
                            unset($x->res_is_indeterminate);
                            unset($x->res_is_kp);
                            unset($x->res_risks_count);
                            unset($x->res_test_recent);
                            unset($x->last_test);
                            unset($x->hiv_status);
                            unset($x->treatment_history);
                            unset($x->gender);
                            unset($x->sex_at_birth);
                            unset($x->sex_with);
                            unset($x->risks);
                        } else {
                            $x->hiv_status = $this->translateCode($x->hiv_status, array('negative', 'positive', 'not_sure', 'not_say'));
                            $x->treatment_history = $this->translateCode($x->treatment_history, array('always_take', 'not_always_take', 'not_yet_treatment', 'not_taking_arv'));
                            $x->gender = $this->translateCode($x->gender, array('male', 'female', 'tg', 'complicated', 'not_say'));
                            $x->sex_at_birth = $this->translateCode($x->sex_at_birth, array('male', 'female', 'not_say'));
                            $x->last_test = $this->translateCode($x->last_test, array('less_6mo', 'more_6mo', 'never'));
                        }

                        $arr = (array)$x;

                        if ($includeAssessmentFields) {
                            $this->explodeColumns($arr);
                        }

                        return $arr;

                    })->toArray();

                    // Write a new row
                    $writer->addRowsWithStyle($rows, $style);
                });

            }

            // Save the context and close the stream
            $writer->close();
        } catch (\Exception $e) {
            Log::error('Error generating Excel export: ' . $e->getMessage());
            //TEST:
            //var_dump($e);
        }

        // Return
        return "";
    }

    function explodeColumns(&$arr)
    {
        //Explode sex with
        $sex_with_count = 5;
        $sex_with = $arr['sex_with'];
        if ($sex_with != '') {
            $sex_with_values = explode(",", $sex_with);
            for ($i = 0; $i < $sex_with_count; $i++) {
                $arr['sex_with_' . $i] = in_array($i, $sex_with_values) ? 1 : 0;
            }
        }
        unset($arr['sex_with']);

        //Explode risks
        $risks_count = 11;
        $risks = $arr['risks'];
        if ($risks != '') {
            $risks_values = explode(",", $risks);
            for ($i = 0; $i < $risks_count; $i++) {
                $arr['risks_' . $i] = in_array($i, $risks_values) ? 1 : 0;
            }
        }
        unset($arr['risks']);
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

    function translateRiskResult($resRiskResult)
    {
        if ($resRiskResult == "9") return "skipped";
        if ($resRiskResult == "PT") return "pos-art";
        if ($resRiskResult == "PN") return "pos-notyet";
        if ($resRiskResult == "PL") return "pos-flu";
        if ($resRiskResult == "PA") return "pos-adherence";
        if ($resRiskResult == "U") return "unknown";
        return $resRiskResult;
    }

    function clearSpecialChars($value)
    {
        $whiteSpace = '\s';  //if you dnt even want to allow white-space set it to ''
        $pattern = '/[^a-zA-Z0-9' . $whiteSpace . ']/u';
        $name = preg_replace($pattern, '', (string)$value);
        //Excel sheet name max length is 31
        if (strlen($name) > 30) {
            $name = substr($name, 0, 30);
        }
        return $name;
    }

}
