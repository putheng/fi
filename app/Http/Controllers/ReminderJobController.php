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
use App\Models\Clinic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class ReminderJobController extends BaseController
{
    static function runBatch()
    {
        $controller = new ReminderJobController();
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

            $dateFrom = date("Y-m-d H:i:s");
            $dateTo = date("Y-m-d H:i:s", strtotime("3 hour"));

            //Find reservations not yet notified, that are scheduled within 3 hours
            $reservations = DB::table('reservations')
                ->whereNull('deleted_at')
                ->where('res_status', ORAConsts::RES_STATUS_RESERVATION)
                ->where('is_arrived', '0')
                ->where('is_reminder_sent', '0')
                ->where('res_date', '>=', $dateFrom)
                ->where('res_date', '<=', $dateTo)
                ->get();

            foreach ($reservations as $res) {

                //Load clinic data
                $clinic = Clinic::find($res->clinic_id);

                //Send the SMS
                $this->sendSMS($clinic, $res);

                //Update the record
                DB::table('reservations')
                    ->where('id', $res->id)
                    ->update(
                        array('is_reminder_sent' => 1)
                    );
            }

        } catch (\Throwable $e) {
            //Log error to file
            Log::error("REMINDER-JOB - Error in SMS scheduled job: " . $e->getMessage());
            //Throw the error so the scheduled job will log it to file
            throw $e;
        }
    }

    function sendSMS($clinic, $reservation)
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

            //TEST:
            //$phoneNum = "9820052135";

            //Prepare the SMS body
            $date = date_format(date_create($reservation->res_date), "d/m");
            $time = date_format(date_create($reservation->res_date), "H:i");

            $smsBody = Lang::get('reminder/title.sms_reminder_body');
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
                } else {
                    Log::info("SMS Sent successfully to: " . $phoneNum);
                }
            } else {
                //We are not in the correct time frame. Store the SMS log record, it will be sent by the scheduled job.
                $this->recordSmsLog($reservation->id, $numbers, $message, $sender, $isTest, 'Current time not in allowed time frame.');
            }

        } catch (\Exception $e) {
            Log::error("Error sending reminder SMS to " . $phoneNum . " - Error: " . $e->getMessage() . " Message: " . $message);

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


}