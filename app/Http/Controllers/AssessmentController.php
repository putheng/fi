<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function reservation(Request $request)
    {
    	dd('ok');
    }

    public function saveReservation($tokenId, $param, $paramR, $kp, $risk)
    {
    	$date = date('Y-m-d H:i:s');
        return DB::table('reservations')
        ->insertGetId([
            'token_id' => $tokenId,
            'res_created_date' => $date,
            'res_assessment_date' => $date,
            'is_arrived' => 0,
            'screened_status' => ORAConsts::RES_SCREENED_STATUS_EMPTY,
            'confirmed_status' => ORAConsts::RES_CONFIRMED_STATUS_EMPTY,
            'res_url_param' => $resUrlParam,
            'res_url_param_r' => $resUrlParamR,
            'res_risk_result' => $riskLevel,
            'res_kp_labels' => $kpLabels,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }

    public function nullableInput(Request $request, $prefix)
    {
    	$value = $request->input($prefix);
        
        return strlen($value) == 0 ? null : $value;
    }

    public function assemblyAnswers(Request $request, $prefix, $count)
    {
        $buffer = [];
        for ($i = 1; $i < $count + 1; $i++) {
            $current = $request->input($prefix . "_r" . ($i < 10 ? "0" : "") . $i);
            if (strlen($current) > 0)
                array_push($buffer, $current);
        }
        return implode(",", $buffer);
    }

    public function saveAssessments(Request $request, $resId)
    {
        $q02 = $this->nullableInput($request, "q02_radio");
        $q03 = $this->nullableInput($request, "q03_radio");
        $q04 = $this->nullableInput($request, "q04_radio");
        $q05 = $this->nullableInput($request, "q05_radio");
        $q06 = $this->nullableInput($request, "q06_radio");

        $is_msm = $this->nullableInput($request, "is_msm");
        $is_sw = $this->nullableInput($request, "is_sw");
        $is_pwid = $this->nullableInput($request, "is_pwid");
        $is_tg = $this->nullableInput($request, "is_tg");
        $is_indeterminate = $this->nullableInput($request, "is_indeterminate");
        $is_kp = $this->nullableInput($request, "is_kp");
        $risks_count = $this->nullableInput($request, "risks_count");
        $test_recent = $this->nullableInput($request, "test_recent");

        // Create the multi answer
        $q07 = $this->assemblyAnswers($request, "q07", 5);
        $q08 = $this->assemblyAnswers($request, "q08", 11);
        // Save
        $date = date('Y-m-d H:i:s');

        DB::table('reservations_assessments')
        ->insert([
            'reservation_id' => $resId,
            'last_test' => $q02,
            'hiv_status' => $q03,
            'treatment_history' => $q04,
            'gender' => $q05,
            'sex_at_birth' => $q06,
            'sex_with' => $q07,
            'risks' => $q08,
            'res_is_msm' => $is_msm,
            'res_is_sw' => $is_sw,
            'res_is_pwid' => $is_pwid,
            'res_is_tg' => $is_tg,
            'res_is_indeterminate' => $is_indeterminate,
            'res_is_kp' => $is_kp,
            'res_risks_count' => $risks_count,
            'res_test_recent' => $test_recent,
            'created_at' => $date,
            'updated_at' => $date
        ]);

        try {

            // Fix params
            $resUrlParam = null;
            $resUrlParamR = null;

            // Get the params
            $token = $this->getToken($tokenNum);

            $kpLabels = $request->input("kp_labels");
            $riskLevel = $request->input("risk_level");

            //Begin transaction
            DB::beginTransaction();

            $id = $this->saveReservation($token->id, $resUrlParam, $resUrlParamR, $kpLabels, $riskLevel);

            $this->saveAssessments($request, $id);

            // Commit transaction
            DB::commit();
            return $id;

        } catch (\Exception $e) {
            \Log::error("Error saving reservation: " . $e->getMessage());
            DB::rollback();
        }

        // Return a generic error to the client
        throw new Exception();
    }

    public function getToken($tokenNum)
    {
        try {

            $query = DB::table('tokens');
            if ($tokenNum >= 0) {
                $query = $query->where('token_num', '=', $tokenNum);
            } else {
                $query = $query->where('is_default', '=', 1);
            }
            $query = $query->whereNull('deleted_at');
            $result = $query->first();

            return $result;
        } catch (\Exception $e) {
            Log::error('Error retrieving token. Number: ' . $tokenNum . " Error: " . $e->getMessage());
        }

        return null;
    }
}
