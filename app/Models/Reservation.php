<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $clinic_id
 * @property integer $token_id
 * @property string $res_code_short
 * @property string $res_code_long
 * @property string $res_created_date
 * @property string $res_date
 * @property string $client_blue_code
 * @property string $client_name
 * @property string $client_birthdate
 * @property string $client_real_first_name
 * @property string $client_real_last_name
 * @property string $client_phone_num
 * @property string $client_line_id
 * @property boolean $confirmed_status
 * @property boolean $is_arrived
 * @property string $clinic_internal_code
 * @property string $clinic_notes
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Clinic $clinic
 * @property Token $token
 */
class Reservation extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['clinic_id', 'token_id', 'res_code_short', 'res_code_long', 'res_created_date', 'res_date',
                        'client_name', 'client_phone_num',
                        'is_arrived', 'sti_status', 'screened_status', 'confirmed_status', 'arrived_date', 'sti_status_date', 'screened_status_date', 'confirmed_status_date',
                        'clinic_internal_code', 'clinic_notes', 'created_at', 'updated_at', 'deleted_at',
                        'res_risk_result'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function token()
    {
        return $this->belongsTo('App\Models\Token');
    }
}
