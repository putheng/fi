<?php

namespace App\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
    	'path'
    ];

    public function question()
    {
    	return $this->belongsTo(Question::class);
    }

    public function path()
    {
    	return '/uploads/' . $this->path;
    }
}
