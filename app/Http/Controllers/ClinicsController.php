<?php namespace App\Http\Controllers;

use App\Http\Common\ORAConsts;
use App\Http\Common\ORAHelper;
use App\Http\Requests\ClinicRequest;
use App\Models\Chain;
use App\Models\Clinic;
use App\Models\Site;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Datatables;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

class ClinicsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission_user');
    }

    public function index()
    {
        $this->setLocalTimezone();
        $currDate = date("Y-m-d H:i:s");

        $user = Sentinel::getUser();
        $suff = App::getLocale() == ORAConsts::LANGUAGE2 ? "_lang2" : "";

        //Select clinics and reservations counts
        $allDesc = Lang::get("clinics/title.clinic_all");
        $sql = "SELECT c.type, c.id, s.sort_index AS site_sort_index, c.sort_index AS clinic_sort_index, IFNULL(c.name$suff, c.name) as name, IFNULL(s.name$suff, s.name) as site_name, c.is_enabled, " .
            " SUM(IF(r.res_date > '$currDate', 1, 0)) as future_res,  " .
            " SUM(IF(r.res_date <= '$currDate', 1, 0)) as old_res_total,  " .
            " SUM(IF(r.res_date <= '$currDate' and (r.screened_status = " . ORAConsts::RES_SCREENED_STATUS_NEGATIVE . " or r.confirmed_status != " . ORAConsts::RES_CONFIRMED_STATUS_EMPTY . "), 1, 0)) as old_res_completed  " .
            " FROM clinics c  " .
            " left outer join reservations r on r.clinic_id = c.id and r.deleted_at IS NULL and r.res_status = " . ORAConsts::RES_STATUS_RESERVATION .
            " left outer join sites s on s.id = c.site_id " .
            " where c.deleted_at IS NULL ";

        //User limited to one site id
        if ($user->site_id != null && $user->site_id != 0) {
            $sql .= " and c.site_id = $user->site_id ";
        }

        $sql .= " GROUP BY id, name, site_name, is_enabled, s.sort_index, c.type, c.sort_index " .
            " ORDER BY site_sort_index, clinic_sort_index, name ";

        $clinics = DB::select($sql);

        return view('admin.clinics.index')
            ->with('clinics', $clinics);
    }

    function getSlotsLengths()
    {
        $lengths = array(5, 10, 15, 20, 25, 30, 45, 60, 75, 90, 120, 150, 180, 240);
        return $lengths;
    }

    function getSites()
    {
        $sites = Site::getEnabled();
        return $sites;
    }

    function getChains()
    {
        $chains = Chain::getEnabled();
        return $chains;
    }

    public function create()
    {
        return view('admin.clinics.create')
            ->with('sites', $this->getSites())
            ->with('chains', $this->getChains())
            ->with('slot_lengths', $this->getSlotsLengths());
    }

    public function store(ClinicRequest $request)
    {
        DB::transaction(function () use ($request) {
            $clinic = new Clinic($request->except('_token'));
            
            $clinic->fill($request->except('_method', '_token'));
            //Special handling for checkboxes. When unchecked they are not passed to the request
            $clinic->is_enabled = isset($request['is_enabled']) ? $request['is_enabled'] : 0;
            $clinic->res_options = isset($request['res_options']) ? $request['res_options'] : 0;

            // Convert empty to null
            if ($clinic->res_options_text == "") $clinic->res_options_text = null;
            if ($clinic->res_options_text_lang2 == "") $clinic->res_options_text_lang2 = null;
            if ($clinic->name_lang2 == "") $clinic->name_lang2 = null;
            if ($clinic->location_desc_lang2 == "") $clinic->location_desc_lang2 = null;
            if ($clinic->address_desc_lang2 == "") $clinic->address_desc_lang2 = null;
            if ($clinic->directions_desc_lang2 == "") $clinic->directions_desc_lang2 = null;
            if ($clinic->res_notes_lang2 == "") $clinic->res_notes_lang2 = null;

            //Save the clinic's image
            $this->storeImage($request, $clinic);

            //Save the record in the database
            $clinic->save();

            //Create default holidays
            $sql = "insert into clinics_holidays (clinic_id,holiday_name,holiday_date,is_recurring) " .
                " select " . $clinic->id . ",holiday_name,holiday_date,is_recurring from default_holidays";
            
            DB::insert($sql);

            //Create default working days
            for ($i = 0; $i < 7; $i++) {
                DB::table('clinics_work_times')->insert(
                    ['clinic_id' => $clinic->id, 'day_num' => $i, 'time_start_1' => '07:30', 'time_end_1' => ($i == 0 ? '12:30' : '20:00')]
                );
            }
        });

        // Prepare the success message
        $success = Lang::get('message.success.create');

        // Redirect to the list page
        return Redirect::route('admin.clinics.index')->with('success', $success);
    }

    public function myClinic()
    {
        //Load the user's own clinic
        $user = Sentinel::getUser();
        $clinicId = $user->clinic_id;
        $clinic = Clinic::where('id', '=', $clinicId)
            ->whereNull('deleted_at')
            ->first();
        if ($clinic != null) {
            $holidays = $clinic->clinicsHolidays()->orderBy('holiday_date')->get();
            $workTimes = $clinic->clinicsWorkTimes()->orderBy('day_num')->get();
            $services = $clinic->clinicsServices()->whereNull('deleted_at')->get();
        }

        return view('admin.clinics.edit', compact('clinic'))
            ->with('holidays', json_encode($holidays, JSON_NUMERIC_CHECK))
            ->with('workTimes', json_encode($workTimes, JSON_NUMERIC_CHECK))
            ->with('services', json_encode($services, JSON_NUMERIC_CHECK))
            ->with('sites', $this->getSites())
            ->with('chains', $this->getChains())
            ->with('isMyClinic', true)
            ->with('slot_lengths', $this->getSlotsLengths());
    }

    public function edit(Clinic $clinic = null)
    {
        $holidays = $clinic->clinicsHolidays()->orderBy('holiday_date')->get();
        $workTimes = $clinic->clinicsWorkTimes()->orderBy('day_num')->get();
        $services = $clinic->clinicsServices()->whereNull('deleted_at')->get();

        return view('admin.clinics.edit', compact('clinic'))
            ->with('holidays', json_encode($holidays, JSON_NUMERIC_CHECK))
            ->with('workTimes', json_encode($workTimes, JSON_NUMERIC_CHECK))
            ->with('services', json_encode($services, JSON_NUMERIC_CHECK))
            ->with('sites', $this->getSites())
            ->with('chains', $this->getChains())
            ->with('isMyClinic', false)
            ->with('slot_lengths', $this->getSlotsLengths());
    }

    public function update(Clinic $clinic, ClinicRequest $request)
    {
        //Fill the clinic object from the received request
        $clinic->fill($request->except('_method', '_token'));

        //Special handling for checkboxes. When unchecked they are not passed to the request
        $clinic->is_enabled = isset($request['is_enabled']) ? $request['is_enabled'] : 0;
        $clinic->res_options = isset($request['res_options']) ? $request['res_options'] : 0;

        // Convert empty to null
        if ($clinic->res_options_text == "") $clinic->res_options_text = null;
        if ($clinic->res_options_text_lang2 == "") $clinic->res_options_text_lang2 = null;
        if ($clinic->name_lang2 == "") $clinic->name_lang2 = null;
        if ($clinic->location_desc_lang2 == "") $clinic->location_desc_lang2 = null;
        if ($clinic->address_desc_lang2 == "") $clinic->address_desc_lang2 = null;
        if ($clinic->directions_desc_lang2 == "") $clinic->directions_desc_lang2 = null;
        if ($clinic->res_notes_lang2 == "") $clinic->res_notes_lang2 = null;

        //Validate the clinic if the user is not admin
        $user = Sentinel::getUser();

        if (!$this->isAdminAny($user)) {
            $clinicId = $user->clinic_id;
            if ($clinic->id != $clinicId) {
                //Error, user not authorized
                throw new \Exception("Unauthorized.");
            }
        }

        DB::transaction(function () use ($clinic, $request) {
            //Save the clinic image
            $this->storeImage($request, $clinic);

            //Save the record in the database
            $clinic->save();

            //Store work times
            $this->storeWorkTimes($clinic, $request);

            //Store holidays
            $this->storeHolidays($clinic, $request);

            //Store services
            $this->storeServices($clinic, $request);
        });

        // Prepare the success message
        $success = Lang::get('message.success.update');

        if ($this->isAdminAny($user)) {
            // Admin returns to the list page
            return Redirect::route('admin.clinics.index')->with('success', $success);
        } else {
            // User stays there
            return Redirect::route('admin.clinics.myclinic')->with('success', $success);
        }
    }

    function storeWorkTimes($clinic, $request)
    {
        //Only update the work times, never insert/delete
        $workTimesData = json_decode($request->get("workTimesData"));
        foreach ($workTimesData as $workTime) {
            DB::table('clinics_work_times')
                ->where('clinic_id', $clinic->id)
                ->where('day_num', $workTime->day_num)
                ->update(
                    ['time_start_1' => $workTime->time_start_1,
                        'time_end_1' => $workTime->time_end_1,
                        'time_start_2' => $workTime->time_start_2 == "" ? null : $workTime->time_start_2,
                        'time_end_2' => $workTime->time_end_2 == "" ? null : $workTime->time_end_2]);
        }
    }

    function storeHolidays($clinic, $request)
    {
        //Delete everything and re-insert
        $holidaysData = json_decode($request->get("holidaysData"));

        DB::table('clinics_holidays')
            ->where('clinic_id', $clinic->id)
            ->delete();

        foreach ($holidaysData as $holiday) {
            DB::table('clinics_holidays')
                ->insert(
                    ['clinic_id' => $clinic->id,
                        'holiday_name' => $holiday->holiday_name,
                        'holiday_date' => $holiday->holiday_date,
                        'is_recurring' => $holiday->is_recurring]);
        }
    }

    function storeServices($clinic, $request)
    {
        // Holders
        $services = json_decode($request->get("servicesData"));
        $alreadyIn = DB::table('clinics_services')
            ->where('clinic_id', $clinic->id)
            ->get();

        // Insert
        foreach ($services as $service) {
            // If not already in add it
            if (!isset($service->id))
                DB::table('clinics_services')
                    ->insert(
                        [
                            'clinic_id' => $clinic->id,
                            'service_desc_lang1' => $service->service_desc_lang1,
                            'service_desc_lang2' => $service->service_desc_lang2,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
        }

        // Update
        foreach ($services as $service) {
            // If already in update it
            if (isset($service->id))
                DB::table('clinics_services')
                    ->where('id', $service->id)
                    ->update(
                        [
                            'service_desc_lang1' => $service->service_desc_lang1,
                            'service_desc_lang2' => $service->service_desc_lang2,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
        }

        // Delete
        foreach ($alreadyIn as $service) {
            if (array_search($service->id, array_column($services, "id")) === false)
                DB::table('clinics_services')
                    ->where('id', $service->id)
                    ->update([
                        'deleted_at' => date('Y-m-d H:i:s')
                    ]);
        }
    }

    function storeImage(ClinicRequest $request, Clinic &$clinic)
    {
        $imageUrl = "";
        //Check if a new image has been uploaded
        if ($file = $request->file('logo_file')) {
            $imageNameOrig = $request->file('logo_file')->getClientOriginalName();
            $imageName = $clinic->code . '.' . $request->file('logo_file')->getClientOriginalExtension();

            $defaultPath = base_path() . '/public';
            $basePath = env('UPLOAD_FOLDER_PATH', $defaultPath);
            $path = "/images/clinics/";
            $origPath = "origin/";

            $request->file('logo_file')->move($basePath . $path, $imageName);
            $imageUrl = url("/") . $path . $imageName;

            //Keep also a copy of the original file
            File::copy($basePath . $path . $imageName, $basePath . $path . $origPath . $imageNameOrig);

            //IMAGE RESIZE - Width 180
            $img = Image::make($basePath . $path . $imageName);
            if ($img->getWidth() > 180) {
                $img->widen(180);
                $img->save();
            }
        }
        //Save the record in the database
        if ($imageUrl != "") {
            $clinic->logo_url = $imageUrl;
        }
    }

    public function delete($id = null)
    {
        $result = false;
        $item = Clinic::find($id);
        if ($item != null) {
            $result = $item->delete();
        }
        if ($result) {
            return Redirect::route('admin.clinics.index')
                ->with('success', Lang::get('message.success.delete'));
        } else {
            return Redirect::route('admin.clinics.index')
                ->with('error', Lang::get('message.error.delete'));
        }
    }

    public function getModalDelete($id = null)
    {
        $error = '';
        $model = '';
        $confirm_route = route('admin.clinics.delete', ['id' => $id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }


}
