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
class Chain extends Model
{
    use SoftDeletes;

    protected $table = 'chains';
	protected $guarded = ['id'];

    public $timestamps = false; //No timestamp columns

    /**
     * @var array
     */
    protected $fillable = ['code', 'name', 'name_lang2', 'billing_code_info', 'billing_code_info_lang2', 'promo_text', 'promo_text_lang2' ];
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

        $query = DB::table('chains AS s')
            ->whereNull('s.deleted_at');

        //User limited to one chain id
        if ($user->chain_id != null && $user->chain_id != 0) {
            $query = $query->where('id', $user->chain_id);
        }

        return $query->select(
                's.id',
                DB::raw('IFNULL(s.name' . $suff . ', s.name) as name')
            )
            ->orderBy('name')
            ->get();
    }

}
