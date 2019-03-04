<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
class Geopoint extends Model
{
    use SoftDeletes;

    protected $table = 'geopoints';
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
}
