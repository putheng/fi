<?php

namespace App\Models;

use App\Models\Info;
use App\Models\Question;
use App\Models\Recommend;
use App\Models\Result;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
    	'path', 'question_id', 'result_id'
    ];

    public function recommend()
    {
        return $this->belongsTo(Recommend::class);
    }

    public function question()
    {
    	return $this->belongsTo(Question::class);
    }

    public function inf()
    {
        return $this->belongsTo(Info::class);
    }

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function path()
    {
    	return '/uploads/' . $this->path;
    }
}
