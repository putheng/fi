<?php

namespace App\Models;

use App\Models\Image;
use App\Models\InfoAnswer;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
	protected $fillable = [
		'title',
		'title_en',
		'image_id'
	];

    public function answers()
    {
    	return $this->hasMany(InfoAnswer::class);
    }

    public function image()
    {
    	return $this->belongsTo(Image::class);
    }
}
