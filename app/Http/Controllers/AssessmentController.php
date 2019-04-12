<?php

namespace App\Http\Controllers;

use App\Http\Common\ORAConsts;
use DB;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function reservation(Request $request)
    {
        $id = $this->saveReservation(
            1,
            null,
            null,
            'INDEX',
            '2' // risk level
        );

        $this->saveAssessments($request, $id);
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
            'res_url_param' => $param,
            'res_url_param_r' => $paramR,
            'res_risk_result' => $risk,
            'res_kp_labels' => $kp,
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }

    public function saveAssessments(
        $resId, $q2, $q3, $q4, $q5, $q6,
        $q7, $q8, $is_msm=0, $is_sw=0, $is_pwid=0,
        $is_tg=0, $is_indeterminate=0, $is_kp=0,
        $risks_count=1, $test_recent=0,
    )
    {
        // Save
        $date = date('Y-m-d H:i:s');

        DB::table('reservations_assessments')
            ->insert([
                'reservation_id' => $resId,
                'last_test' => $q2, // question 2
                'hiv_status' => $q3, // question 3
                'treatment_history' => $q4, // question 4
                'gender' => $q5, // question 5
                'sex_at_birth' => $q6, // question 6
                'sex_with' => $q7, // question 7
                'risks' => $q8, // question 8
                'res_is_msm' => $is_msm, // 0
                'res_is_sw' => $is_sw, // 0
                'res_is_pwid' => $is_pwid, // 0
                'res_is_tg' => $is_tg, // 0
                'res_is_indeterminate' => $is_indeterminate, // 1
                'res_is_kp' => $is_kp, // 0
                'res_risks_count' => $risks_count, //1
                'res_test_recent' => $test_recent, // 0
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

            return DB::table('tokens')
                ->where('is_default', '=', 1)
                ->whereNull('deleted_at')
                ->first();

        } catch (\Exception $e) {
            Log::error('Error retrieving token. Number: ' . $tokenNum . " Error: " . $e->getMessage());
        }

        return null;
    }
}
