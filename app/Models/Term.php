<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
	protected $fillable = [
		'heading', 'heading_en', 'title', 'title_en', 'subtitle', 'subtitle_en',
    	'bradcume', 'bradcume_en', 'term', 'term_en', 'note', 'note_en', 'image_id'
	];

	public function getTitle()
	{
    	$language = \Lang::get('term.title');

    	return $this->{$language};
	}

	public function getHeading()
	{
    	$language = \Lang::get('term.heading');

    	return $this->{$language};
	}

	public function getSubtitle()
	{
    	$language = \Lang::get('term.subtitle');

    	return $this->{$language};
	}

	public function getNote()
	{
    	$language = \Lang::get('term.note');

    	return $this->{$language};
	}

	public function getTerm()
	{
    	$language = \Lang::get('term.term');

    	return $this->{$language};
	}

	public function getBradcume()
	{
    	$language = \Lang::get('term.title');

    	return $this->{$language};
	}

    public function image()
    {
    	return $this->belongsTo(Image::class);
    }
}
