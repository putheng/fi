<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class Recommend extends Model
{
    protected $fillable = [
    	'title',
    	'title_en',
    	'image_id'
    ];

    public function image()
    {
    	return $this->belongsTo(Image::class);
    }

    public function getTitle()
    {
    	$language = \Lang::get('page.recommend_lang');

    	return $this->{$language};
    }

    public function getDescription()
    {
    	$language = \Lang::get('page.recommend_description');

    	return $this->{$language};
    }
}
