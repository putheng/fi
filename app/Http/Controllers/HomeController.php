<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Models\Clinic;
use App\Models\Question;
use App\Models\Recommend;
use App\Models\Result;
use App\Models\Site;
use App\Models\Term;
use Illuminate\Http\Request;
use MongoClient;

class HomeController extends Controller
{
    public function index()
    {
    	$questions = Question::with('answers')->get();
    	$results = Result::with('image')->get();
        $recommend = Recommend::get()->first();
        $term = Term::find(1);
        
    	return view('template', compact('questions', 'results', 'recommend', 'term'));
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
        $sites = Site::get();

        return view('map', compact('sites'));
    }
}
