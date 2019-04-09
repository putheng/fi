<?php

namespace App\Models;

use App\Models\Info;
use Illuminate\Database\Eloquent\Model;

class InfoAnswer extends Model
{
	protected $fillable = [
		'title',
		'title_en',
	];

    public function question()
    {
    	return $this->belongsTo(Info::class, 'info_id');
    }
}
