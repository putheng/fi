<?php

namespace App\Http\Controllers;

use App\Http\Common\ORAConsts;
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


class TrafficLogsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission_user');
    }

    public function index()
    {
        $dateStart = null;
        $dateEnd = null;

        return view('admin.trafficlogs.index')
            ->with('dateStart', $dateStart)
            ->with('dateEnd', $dateEnd);
    }

    public function getColumnHeaders()
    {
        $headers = Schema::getColumnListing("traffic_logs");

        foreach ($headers as &$header) {
            $header = Lang::get("trafficlogs/title." . $header);
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

    public function export(Request $request)
    {
        try {
            //First validate the user input
            $user = Sentinel::getUser();

            //Validate input dates and convert to MySql format
            $dateFrom = $this->validateDate($request->input('date_from'), ' 00:00:00');
            $dateTo = $this->validateDate($request->input('date_to'), ' 25:59:59');

            // Response settings
            ini_set('max_execution_time', 3600); // Set max execution to 1 hour
            ini_set('memory_limit', '256M'); // Set max memory

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
            $writer->openToBrowser("TrafficLog_" . date("Ymd_Hi") . ".xlsx");
            //$writer->openToFile("D:/TrafficLog_" . date("Ymd_Hi") . ".xlsx");

            $sheet = $writer->getCurrentSheet();
            $sheet->setName("TRAFFIC");
            $headers = $this->getColumnHeaders();
            $writer->addRowWithStyle($headers, $headerStyle);

            $query = DB::table("traffic_logs");
            if ($dateFrom != '') {
                $query = $query->where('log_date', '>=', $dateFrom);
            }
            if ($dateTo != '') {
                $query = $query->where('log_date', '<=', $dateTo);
            }

            $query = $query
                ->orderBy('log_date')
                ->select('*');

//            TEST RESULTS:
//            try {
//                $res = $query->get();
//                var_dump($res);
//            } catch (\Exception $e) {
//                var_dump($e);
//            }

            $query->chunk(5000, function ($rows) use ($writer, $style) {

                //Convert the results into an array of arrays
                $rows = collect($rows)->map(function ($x) {
                    $arr = (array)$x;
                    return $arr;
                })->toArray();

                // Write a new row
                $writer->addRowsWithStyle($rows, $style);

            });

            // Save the context and close the stream
            $writer->close();
        } catch (\Exception $e) {
            Log::error('Error generating traffic logs export: ' . $e->getMessage());
            //TEST:
            //var_dump($e);
        }

        // Return
        return "";
    }

}
