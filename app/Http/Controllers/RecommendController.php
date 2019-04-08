<?php

namespace App\Http\Controllers;

use App\Models\Recommend;
use Illuminate\Http\Request;

class RecommendController extends Controller
{
    public function create()
    {
    	return view('admin.recommend.create');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'titleKh' => 'required',
    		'titleEn' => 'required',
    		'image' => 'required'
    	]);

    	$recommend = new Recommend;

    	$recommend->title = $request->titleKh;
    	$recommend->title_en = $request->titleEn;
    	$recommend->image_id = $request->image;
    	$recommend->save();

    	return back()->withSuccess('Successfuly created');
    }

    public function edit(Request $request, Recommend $recommend)
    {
    	$recommend->load('image');

    	return view('admin.recommend.edit', compact('recommend'));
    }

    public function update(Request $request, Recommend $recommend)
    {
    	$this->validate($request, [
    		'titleKh' => 'required',
    		'titleEn' => 'required',
    		'description_en' => 'required',
            'description' => 'required',
            'heading' => 'required',
    		'heading_en' => 'required',
    	]);

    	$recommend->title = $request->titleKh;
    	$recommend->title_en = $request->titleEn;
        
    	$recommend->description_en = $request->description_en;
        $recommend->description = $request->description;

        $recommend->heading = $request->heading;
    	$recommend->heading_en = $request->heading_en;

    	if(!empty($request->image)){
    		$recommend->image_id = $request->image;
    	}
    	
    	$recommend->save();
    	
    	return redirect()->route('admin.question.index')->withSuccess('Successfuly created');
    }
}
