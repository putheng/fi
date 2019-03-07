<?php

namespace App\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
	protected $fillable = [
		'title',
		'titleEn',
		'point'
	];

    public function question()
    {
    	return $this->belongsTo(Question::class);
    }
}
