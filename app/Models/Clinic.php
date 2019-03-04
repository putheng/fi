<?php

namespace App\Models;

use App\Http\Common\ORAConsts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sentinel;

class Clinic extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = [
        'site_id', 'chain_id',
        'code', 'name',
        'location_desc', 'address_desc', 'directions_desc',
        'email', 'phone_num',
        'website_url', 'video_url', 'logo_url', 'forward_url',
        'res_notes', 'res_time_slot_length', 'sort_index', 'is_enabled',
        'res_options', 'res_options_text', 'res_options_text_lang2',
        'days_visible',
        'created_at', 'updated_at', 'deleted_at',
        'name_lang2', 'location_desc_lang2', 'address_desc_lang2', 'directions_desc_lang2', 'res_notes_lang2', 'gps_lat', 'gps_long'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo('App\Models\Site');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chain()
    {
        return $this->belongsTo('App\Models\Chain');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clinicsHolidays()
    {
        return $this->hasMany('App\Models\ClinicsHoliday');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clinicsUsers()
    {
        return $this->hasMany('App\Models\UsersClinic');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clinicsWorkTimes()
    {
        return $this->hasMany('App\Models\ClinicsWorkTime');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clinicsServices()
    {
        return $this->hasMany('App\Models\ClinicsService');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany('App\Models\Reservation');
    }

    public static function getById($clinicId)
    {
        $suff = App::getLocale() == ORAConsts::LANGUAGE2 ? "_lang2" : "";

        return DB::table('clinics AS c')
            ->select('c.id',
                'c.code',
                DB::raw('IFNULL(c.name' . $suff . ', c.name) as name'),
                'c.site_id',
                'c.chain_id',
                DB::raw('IFNULL(c.location_desc' . $suff . ', c.location_desc) as location_desc'),
                DB::raw('IFNULL(c.address_desc' . $suff . ', c.address_desc) as address_desc'),
                DB::raw('IFNULL(c.directions_desc' . $suff . ', c.directions_desc) as directions_desc'),
                'c.gps_lat',
                'c.gps_long',
                'c.email',
                'c.phone_num',
                'c.website_url',
                'c.video_url',
                'c.logo_url',
                'c.forward_url',
                DB::raw('IFNULL(c.res_notes' . $suff . ', c.res_notes) as res_notes'),
                'c.res_time_slot_length',
                'c.sort_index',
                'c.is_enabled',
                'c.res_options',
                DB::raw('IFNULL(c.res_options_text' . $suff . ', c.res_options_text) as res_options_text'),
                'c.days_visible',
                'c.created_at',
                'c.updated_at',
                'c.deleted_at')
            ->where('c.id', '=', $clinicId)
            ->first();
    }

    public static function getEnabled()
    {
        $suff = App::getLocale() == ORAConsts::LANGUAGE2 ? "_lang2" : "";

        return DB::table('clinics AS c')
            ->where('c.is_enabled', 1)
            ->whereNull('c.deleted_at')
            ->select(
                'c.id',
                DB::raw('IFNULL(c.name' . $suff . ', c.name) as name')
            )
            ->orderBy('name')
            ->get();
    }

    public static function getUserClinicsCombo($usersClinics, $emptyItem = false)
    {
        $suff = App::getLocale() == ORAConsts::LANGUAGE2 ? "_lang2" : "";

        $clinics = DB::table('clinics AS c')
            ->leftJoin('sites AS s', 'c.site_id', 's.id')
            ->whereIn('c.id', $usersClinics)
            ->where('c.is_enabled', 1)
            ->whereNull('c.deleted_at')
            ->select(
                'c.id',
                DB::raw('IFNULL(c.name' . $suff . ', c.name) as name'),
                DB::raw('IFNULL(s.name' . $suff . ', s.name) as site_name')
            )
            ->orderBy('s.sort_index')
            ->orderBy('c.sort_index')
            ->orderBy('name')
            ->get();

        $attributes = array();
        if ($emptyItem) {
            $all1 = Lang::get('export/title.all_separate');
            $attributes[0] = $all1;

            $all2 = Lang::get('export/title.all_single');
            $attributes[-1] = $all2;
        }
        foreach ($clinics as $clinic) {
            if (!isset($attributes[$clinic->site_name])) {
                $attributes[$clinic->site_name] = array();
            }
            $attributes[$clinic->site_name][$clinic->id] = $clinic->name;
            //$attributes[$clinic->id] = $clinic->name;
        }

        return $attributes;
    }

    public static function getEnabledCombo($emptyItem = false)
    {
        $suff = App::getLocale() == ORAConsts::LANGUAGE2 ? "_lang2" : "";
        $user = Sentinel::getUser();

        $query = DB::table('clinics AS c')
            ->leftJoin('sites AS s', 'c.site_id', 's.id')
            ->where('c.is_enabled', 1)
            ->whereNull('c.deleted_at');

        //User limited to one site id
        if ($user->site_id != null && $user->site_id != 0) {
            $query = $query->where('c.site_id', $user->site_id);
        }

        $clinics = $query->select(
                'c.id',
                DB::raw('IFNULL(c.name' . $suff . ', c.name) as name'),
                DB::raw('IFNULL(s.name' . $suff . ', s.name) as site_name')
            )
            ->orderBy('s.sort_index')
            ->orderBy('c.sort_index')
            ->orderBy('c.name')
            ->get();

        $attributes = array();
        if ($emptyItem) {
            $all1 = Lang::get('export/title.all_separate');
            $attributes[0] = $all1;

            $all2 = Lang::get('export/title.all_single');
            $attributes[-1] = $all2;
        }
        foreach ($clinics as $clinic) {
            if (!isset($attributes[$clinic->site_name])) {
                $attributes[$clinic->site_name] = array();
            }
            $attributes[$clinic->site_name][$clinic->id] = $clinic->name;
            //$attributes[$clinic->id] = $clinic->name;
        }

        return $attributes;
    }

}
