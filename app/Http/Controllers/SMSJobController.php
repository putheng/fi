<?php
/**
 * Created by PhpStorm.
 * User: mpravato
 * Date: 6/3/2018
 * Time: 1:00 PM
 */

namespace App\Http\Controllers;


use App\Helpers\Textlocal;
use App\Http\Common\ORAConsts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SMSJobController extends BaseController
{
    static function runBatch()
    {
        $controller = new SMSJobController();
        $controller->runJob();
    }

    public function runManual()
    {
        $this->runJob();
    }

    public function runJob()
    {
        try {
            $this->setLocalTimezone();

            $smsLogs = DB::table('sms_logs')
                ->where('status', ORAConsts::SMS_STATUS_RETRY)
                ->get();

            foreach ($smsLogs as $smsLog) {
                $status = ORAConsts::SMS_STATUS_RETRY;
                $errorMessage = "";

                try {
                    $apiKey = env('SMS_API_KEY');
                    $textlocal = new Textlocal(false, false, $apiKey);
                    $numbers = explode(",", $smsLog->sms_number);
                    $message = $smsLog->sms_message;
                    $sender = env('SMS_SENDER');
                    $isTest = env('SMS_TEST');

                    $result = $textlocal->sendSms($numbers, $message, $sender, null, $isTest);
                    if ($result->status != "success") {
                        throw new \Exception("Unexpected result: " . $result->status);
                    }

                    $status = ORAConsts::SMS_STATUS_SUCCESS;
                } catch (\Exception $e) {
                    echo(date("Y-m-d H:i:s", time()) . " SMS-JOB - Error sending reservation SMS to " . $smsLog->sms_number . " - Error: " . $e->getMessage() . "\n");
                    $errorMessage = $e->getMessage();

                    if ($smsLog->retry_count >= ORAConsts::SMS_MAX_RETRY) {
                        $status = ORAConsts::SMS_STATUS_ERROR;
                    }
                }

                if ($status == ORAConsts::SMS_STATUS_SUCCESS) {
                    //Delete the record
                    DB::table('sms_logs')
                        ->where('id', $smsLog->id)
                        ->delete();
                } else {
                    //Update the record
                    DB::table('sms_logs')
                        ->where('id', $smsLog->id)
                        ->update(
                            array('status' => $status,
                                'retry_count' => $smsLog->retry_count + 1,
                                'error_message' => $errorMessage,
                                'last_try_at' => DB::raw('now()')
                            )
                        );
                }
            }
        } catch (\Throwable $e) {
            //Log error to file
            Log::error("SMS-JOB - Error in SMS scheduled job: " . $e->getMessage());
            //Throw the error so the scheduled job will log it to file
            throw $e;
        }
    }

}