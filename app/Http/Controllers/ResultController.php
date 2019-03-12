<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
    	return view('admin.question.results.create');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'resultKh' => 'required',
    		'resultEn' => 'required',
    		'from' => 'required',
    		'to' => 'required',
    	]);

    	$result = Result::create([
    		'title' => $request->resultKh,
    		'titleEn' => $request->resultEn,
    		'from' => $request->from,
    		'to' => $request->to,
    	]);

    	return redirect()->route('admin.question.index');
    }

    public function edit(Request $request, Result $result)
    {
    	return view('admin.question.results.edit', compact('result'));
    }

    public function update(Request $request, Result $result)
    {
    	$this->validate($request, [
    		'resultKh' => 'required',
    		'resultEn' => 'required',
    		'from' => 'required',
    		'to' => 'required',
    	]);

    	$result->update([
    		'title' => $request->resultKh,
    		'titleEn' => $request->resultEn,
    		'from' => $request->from,
    		'to' => $request->to,
    	]);

    	return redirect()->route('admin.question.index');
    }

    public function destroy(Request $request, Result $result)
    {
    	$result->delete();

    	return back();
    }
}
