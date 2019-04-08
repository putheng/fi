<?php

namespace App\Http\Controllers;

use App\Http\Requests\TermFormRequest;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function edit()
    {
    	$term = Term::find(1);

    	return view('admin.term.edit', compact('term'));
    }

    public function store(TermFormRequest $request)
    {

    	$term = Term::find(1);

    	$array = ['heading', 'heading_en', 'title', 'title_en', 'subtitle', 'subtitle_en',
    			'bradcume', 'bradcume_en', 'term', 'term_en', 'note', 'note_en'];

    	if(!empty($request->image_id)){
    		$array = array_merge($array, ['image_id']);
    	}

    	$term->update($request->only($array));

    	return back()->withSuccess('Update successfully');
    }
}
