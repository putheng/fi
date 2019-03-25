<?php

namespace App\Http\Controllers;

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

    public function map()
    {
        // $mongo = \DB::connection('mongodb')->table('locations')->delete();
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
            'spherical' => true,
            'maxDistance' => $distance,
        ]);

        $locations = $location['results'];

        dd($locations);
    }

    public function mapx()
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

        // $mongo->createIndex(['location' => '2dsphere']);
        // return view('home.map', compact('clinics'));
    }
}
