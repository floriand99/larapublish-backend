<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function __construct(){
        $this->middleware('auth:api');
    }
    public function store(Request $request){
        $file = $request->file('file')->store('images');
        return ['image' => $file];
    }

    public function destroy(Request $request){
        $image = $request->image;
        Storage::delete($image);
    }
}
