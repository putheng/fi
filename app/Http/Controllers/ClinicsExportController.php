<?php

namespace App\Http\Controllers;

use App\Exports\ClinicExport;
use App\Imports\ClinicImport;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClinicsExportController extends Controller
{
	public function index()
	{
		return view('admin.clinics.import');
	}

    public function export(Request $request)
    {
    	$file = 'export_'. date('Y') .'_'. date('m') .'_'. date('d') .'.xlsx';
    	
    	return Excel::download(new ClinicExport, $file);
    }

    public function import(Request $request) 
    {
    	try{
            Excel::import(new ClinicImport, $request->file('excel'));
        }catch(Exception $e){

        }
        
        return back()->with('success', 'import successfully');
    }
}
