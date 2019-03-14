<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->file('file');

        $id = strtolower(uniqid(true) . $file->getClientOriginalName());
        Storage::disk('asset')->put($id, file_get_contents($request->file('file')));


        $image = new Image;
        $image->path = $id;

        $image->save();

        return response()->json([
            'id' => $image->id,
            'path' => $image->path()
        ]);
    }
}
