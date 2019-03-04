<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @property integer $id
 * @property integer $clinic_id
 * @property string $service_desc_lang1
 * @property string $service_desc_lang2
 * @property Clinic $clinic
 */
 class ClinicsService extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['clinic_id', 'service_desc_lang1', 'service_desc_lang2'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic');
    }
}