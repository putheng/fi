<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Result;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
    	$questions = Question::with('answers')->get();
    	$results = Result::with('image')->get();

    	return view('template', compact('questions', 'results'));
    }

    public function show()
    {
      	$questions = Question::with('answers')->get();
    	$results = Result::with('image')->get();
    	
    	return view('home.index', compact('questions', 'results'));
    }

    public function template()
    {
        $questions = Question::with('answers')->get();
        $results = Result::with('image')->get();
        
        return view('home.show', compact('questions', 'results'));
    }
}
