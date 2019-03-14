<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
    	'title',
    	'titleEn',
    	'from',
    	'to'
    ];

    public function image()
    {
    	return $this->hasOne(Image::class);
    }
}
