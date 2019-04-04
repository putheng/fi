<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
	public function index(Request $request, Clinic $clinic)
	{
		$working = $clinic->clinicsWorkTimes;

		return view('reservation.index', compact('clinic', 'working'));
	}
}
