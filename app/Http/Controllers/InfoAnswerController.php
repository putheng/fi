<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\InfoAnswer;
use Illuminate\Http\Request;

class InfoAnswerController extends Controller
{
    public function create(Request $request, Info $info)
    {
    	return view('admin.info.answer', compact('info'));
    }

    public function store(Request $request, Info $info)
    {
    	$this->validate($request, [
    		'answer.*' => 'required',
    		'answer_en.*' => 'required'
    	],[
    		'answer.*.required' => 'You must give this answer a title',
    		'answer_en.*.required' => 'You must give this answer a title',
    	]);

    	$info->answers->each(function ($part, $index) use ($request) {
            $part->timestamps = false;
            $part->update([
            	'title' => $request->answer[$index],
            	'title_en' => $request->answer_en[$index],
           	]);
        });

    	if(!empty($request->title) && !empty($request->title_en)){
	    	$answer = new InfoAnswer;
	    	$answer->title = $request->title;
	    	$answer->title_en = $request->title_en;
	    	$answer->question()->associate($info);
	    	$answer->save();
    	}
    	return back();
    }

    public function destroy(Request $request, InfoAnswer $answer)
    {
    	$answer->delete();

    	return back()->withSuccess('Answer was deleted');
    }
}
