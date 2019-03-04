<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ClinicsWorkTime extends Model
{
    public $timestamps = false; //No timestamp columns

    /**
     * @var array
     */
    protected $fillable = ['clinic_id', 'day_num', 'time_start_1', 'time_end_1', 'time_start_2', 'time_end_2'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic');
    }
}
