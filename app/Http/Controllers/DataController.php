<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Models\Clinic;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
    	$clinics = Clinic::filter()->get();

        return LocationResource::collection($clinics);
    }
}
