<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::get();

    	return view('admin.question.index', compact('questions'));
    }

    public function create()
    {
    	return view('admin.question.create');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'titleKh' => 'required|unique:questions',
    		'titleEn' => 'required|unique:questions'
    	]);

    	$question = Question::create($request->only('titleKh', 'titleEn'));

    	return redirect()->route('admin.question.answer', $question);
    }
}
