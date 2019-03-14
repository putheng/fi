<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::get();
        $results = Result::get();

    	return view('admin.question.index', compact('questions', 'results'));
    }

    public function create()
    {
    	return view('admin.question.create');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'titleKh' => 'required|unique:questions',
    		'titleEn' => 'required|unique:questions'
    	]);

    	$question = Question::create($request->only('titleKh', 'titleEn', 'type'));

        if(!empty($request->image)){
            $image = Image::find($request->image);

            $image->update(['question_id' => $question->id]);
        }

    	return redirect()->route('admin.question.answer', $question);
    }

    public function edit(Question $question)
    {
        return view('admin.question.edit', compact('question'));
    }

    public function storeUpdate(Request $request, Question $question)
    {
        $this->validate($request, [
            'titleKh' => 'required',
            'titleEn' => 'required',
        ]);

        $question->update($request->only('titleKh', 'titleEn', 'type'));

        return back();
    }

    public function deleteQ(Request $request, Question $question)
    {
        $question->delete();

        return back();
    }

    public function storeFile(Request $request, Question $question)
    {
        $file = $request->file('file');

        $id = strtolower(uniqid(true) . $file->getClientOriginalName());
        Storage::disk('asset')->put($id, file_get_contents($request->file('file')));

        if($question->image){
            $question->image()->delete();
        }

        $image = new Image;
        $image->path = $id;
        $image->question()->associate($question);

        $image->save();

        return response()->json([
            'id' => $image->id,
            'path' => $image->path()
        ]);
    }
}
