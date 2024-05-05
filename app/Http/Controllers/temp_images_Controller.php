<?php

namespace App\Http\Controllers;

use App\Models\temp_image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class temp_images_Controller extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|image'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Image required',
                'error' => $validator->errors()
            ]);
        }

        // upload image 
        $image = $request->file('name'); // Change to 'name'
        $ext = $image->getClientOriginalExtension(); // Correct typo
        $imagename = time().'.'.$ext;

        $tempImage = new temp_image;
        $tempImage->name = $imagename;
        $tempImage->save();
    
        // move image
        $image->move(public_path('uploads/temp'), $imagename); // Correct move() arguments
    
        return response()->json([
            'status' => true,
            'message' => 'Image uploaded successfully',
            'data' => $tempImage
        ]);
    }
}
