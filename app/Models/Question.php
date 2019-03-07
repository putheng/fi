<?php

namespace App\Models;

use App\Models\Answer;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
    	'titleKh',
    	'titleEn'
    ];

    public function answers()
    {
    	return $this->hasMany(Answer::class);
    }
}
