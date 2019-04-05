<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
    	'titleKh',
    	'titleEn',
    	'type',
        'shortEn',
        'header',
        'header_en',
        'subtitle',
        'subtitleEn',
    ];

    public function answers()
    {
    	return $this->hasMany(Answer::class);
    }

    public function image()
    {
    	return $this->hasOne(Image::class);
    }
}
