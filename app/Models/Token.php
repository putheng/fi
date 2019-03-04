<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes;

    public $table = 'tokens';
    protected $fillable = [
        'token_num', 'is_default', 'purpose_desc', 'title', 'fe_default_lang', 'fe_image_url', 'fe_welcome_text', 'fe_snippet_text', 'fe_welcome_text_lang2', 'fe_snippet_text_lang2',
        'fe_color_1', 'fe_color_2', 'fe_color_3', 'fe_color_4', 'return_url', 'fe_welcome_text_size', 'is_incentive', 'skip_risk_assessment'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany('App\Reservation');
    }

}
