<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Models\Clinic;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Http\Request;
use MongoClient;

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

    public function data(Request $request)
    {
        $clinics = Clinic::filter()->get();

        return LocationResource::collection($clinics);
    }

    public function map()
    {
        return view('map');
    }
}
