<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function answerkh(Question $question)
    {
    	return view('admin.question.answer', compact('question'));
    }

    public function store(Request $request, Question $question)
    {
    	$this->validate($request, [
    		'answer.*' => 'required',
    		'answerEn.*' => 'required'
    	],[
    		'answer.*.required' => 'You must give this answer a title',
    		'answerEn.*.required' => 'You must give this answer a title',
    	]);

    	$question->answers->each(function ($part, $index) use ($request) {
            $part->timestamps = false;
            $part->update([
            	'title' => $request->answer[$index],
            	'point' => $request->point[$index],
            	'titleEn' => $request->answerEn[$index],
           	]);
        });

    	if(!empty($request->title)){
	    	$answer = new Answer;
	    	$answer->title = $request->title;
	    	$answer->point = $request->points;
	    	$answer->titleEn = $request->titleEn;
	    	$answer->question()->associate($question);
	    	$answer->save();
    	}
    	return back();
    }

    public function destroy(Answer $answer)
    {
    	$answer->delete();
    	return back();
    }

}
