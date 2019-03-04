<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $clinic_id
 * @property string $holiday_name
 * @property string $holiday_date
 * @property boolean $is_recurring
 * @property Clinic $clinic
 */
class ClinicsHoliday extends Model
{
    public $timestamps = false; //No timestamp columns

    /**
     * @var array
     */
    protected $fillable = ['clinic_id', 'holiday_name', 'holiday_date', 'is_recurring'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic');
    }
}
