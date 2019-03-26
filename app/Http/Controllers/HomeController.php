<?php

namespace App\Http\Controllers;

use MongoClient;
use App\Models\Clinic;
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

    public function mapz()
    {
        $mongo = \DB::connection('mongodb')->table('locations')->get();

        return $mongo->toArray();
    }

    public function mapx()
    {
        $mongo = new MongoClient('mongodb://localhost:27017');
    }

    public function mapa()
    {
        
        $mongo = \DB::connection('mongodb');

        $lat = 11.548109;
        $lng = 104.932713;
        $distance = 5000;

        $location = $mongo->command([
            'geoNear' => 'locations',
            'near' => [
                'type' => 'Point',
                'coordinates' => [
                    (float) $lat,
                    (float) $lng
                ],
            ],
            'sphercial' => true,
            'maxDistance' => $distance,
        ]);

        $locations = $location['results'];

        dd($locations);
    }

    public function map()
    {
        $clinics = Clinic::select(
            'name',
            'location_desc',
            'address_desc',
            'gps_lat AS latitude',
            'gps_long AS longitude'
        )->get();

        $mongo = \DB::connection('mongodb')->table('locations');

        $clinics->each(function($clinic, $index) use ($mongo){
            $mongo->insert([
                'name' => $clinic->name,
                'address' => $clinic->address_desc,
                'location' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $clinic->longitude,
                        (float) $clinic->latitude,
                    ]
                ]
            ]);
        });

        return $mongo->get()->toArray();
        
        // $mongo->createIndex(['location' => '2dsphere']);
        // return view('home.map', compact('clinics'));
    }
}
