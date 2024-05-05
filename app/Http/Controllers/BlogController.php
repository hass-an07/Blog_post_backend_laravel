<?php

namespace App\Http\Controllers;

use App\Models\blog;
use App\Models\temp_image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    // this method will return all blogs
    public function index(Request $request)
    {
        $blogs = blog::orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $blog = $blogs->where('title', 'like', '%' . $request->keyword . '%');
        }

        $blog = $blogs->get();

        return response()->json([
            'status' => true,
            'message' => 'record fetched',
            'data' => $blog
        ]);
    }

    // this method will return single blogs
    public function show($id)
    {
        $blog = blog::find($id);
        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'id not found',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'record fetched',
            'data' => $blog
        ]);
    }

    // this method will store blogs
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:10',
            'author' => 'required|min:3',
            'image' => 'image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'error' => $validator->errors()
            ]);
        }




        $image = $request->file('image');
        $ext = $image->getClientOriginalExtension();

        // Validate if the extension is allowed

        $imagename = time() . '.' . $ext;
        $image->move(public_path('uploads/temp'), $imagename);

        $blog = new Blog; // Assuming your model is named 'Blog'
        $blog->title = $request->title;
        $blog->author = $request->author;
        $blog->image = $imagename;
        $blog->description = $request->description;
        $blog->short_desc = $request->short_desc;
        $blog->save();


        return response()->json([
            'status' => true,
            'message' => 'Blog added successfully',
            'data' => $blog
        ]);
    }
    // this method will update blogs
    public function update($id, Request $request)
    {
        
        $update_blog = blog::find($id);
        if($update_blog === null){
            return response()->json([
                'status' => false,
                'message' => 'record not found'
            ]);
        }

        $update_blog->title = $request->title;
        $update_blog->author = $request->author;
        $update_blog->description = $request->description;
        $update_blog->short_desc = $request->short_desc;
        $update_blog->save();

        if (!$update_blog->save()) {
            return response()->json([
                'status' => false,
                'message' => 'error to update data',
                "error" => $update_blog->getErrors(),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'record updated successfully',
            "data" => $update_blog,
        ]);
    }
    // this method will delete blogs
    public function destroy($id)
    {
        $delete_blog = blog::destroy($id);
        if ($delete_blog === false) {
            return response()->json([
                'status' => false,
                'message' => 'record not delete',
                "error" => $delete_blog->getErrors(),
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'record  delete',
            "error" => $delete_blog,
        ]);
    }
}
