<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;

class InfoController extends Controller
{
	public function create(Request $request)
	{
		return view('admin.info.create');
	}

    public function edit(Info $info)
    {
    	return view('admin.info.edit', compact('info'));
    }

    public function update(Request $request, Info $info)
    {
    	$this->validate($request, [
    		'title' => 'required',
    		'title_en' => 'required'
    	]);

    	$info->update($request->only('title', 'title_en', 'image_id'));

    	return back()->withSuccess('Updated');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'title' => 'required',
    		'title_en' => 'required'
    	]);

    	Info::create($request->only('title', 'title_en', 'image_id'));

    	return redirect()->route('admin.question.index')->withSuccess('Updated');
    }

    public function destroy(Request $request, Info $info)
    {
        $info->delete();

        return back()->withSuccess('Info was deleted');
    }
}
