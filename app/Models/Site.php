<?php

namespace App\Models;

use App\Http\Common\ORAConsts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Sentinel;

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $name_lang2
 * @property string $gps_lat
 * @property string $gps_long
 * @property integer $sort_index
 * @property Clinic[] $clinics
 */
class Site extends Model
{
    use SoftDeletes;

    protected $table = 'sites';
	protected $guarded = ['id'];

    public $timestamps = false; //No timestamp columns

    /**
     * @var array
     */
    protected $fillable = ['code', 'name', 'name_lang2', 'sort_index', 'gps_lat', 'gps_long'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clinics()
    {
        return $this->hasMany('App\Models\Clinic');
    }

    public static function getEnabled()
    {
        $suff = App::getLocale() == ORAConsts::LANGUAGE2 ? "_lang2" : "";
        $user = Sentinel::getUser();

        $query = DB::table('sites AS s')
            ->whereNull('s.deleted_at');

        //User limited to one site id
        if ($user->site_id != null && $user->site_id != 0) {
            $query = $query->where('id', $user->site_id);
        }

        return $query->select(
                's.id',
                DB::raw('IFNULL(s.name' . $suff . ', s.name) as name')
            )
            ->orderBy('sort_index')
            ->get();
    }

    public function getTitleAttribute()
    {
        $language = \Lang::get('page.clinic_site');

        return $this->{$language};
    }

}
