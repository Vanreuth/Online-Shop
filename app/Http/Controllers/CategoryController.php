<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Display a listing of categories
    public function index()
    {
        $categories = Category::all(); 
        return view('back-end.category', compact('categories'));
    }

    public function upload(Request $request)
{
    // Validate the request
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Handle the uploaded file
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/temp'), $filename, 'public'); // Ensure 'public' disk is set in config/filesystems.php

        return response()->json([
            'status' => 200,
            'image' => $filename,
        ]);
    }

    return response()->json(['status' => 400, 'message' => 'No image uploaded']);
}


    public function cancel(Request $request)
    {
        if ($request->has('image')) {
            $imagePath = public_path('uploads/temp/' . $request->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
    
                return response()->json(['status' => 200, 'message' => 'Image deleted successfully']);
            } else {
                return response()->json(['status' => 404, 'message' => 'Image not found']);
            }
        }
        return response()->json(['status' => 400, 'message' => 'No image provided']);
    }

    // Store a new category in the database
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'status' => 'required|boolean',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Ensure the image is validated as a file
    ]);

    $category = new Category();
    $category->name = $request->name;
    $category->status = $request->status;

    // Handle the uploaded file
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/category'), $filename); // Move to final directory
        $category->image = $filename; // Save image filename in the database
    }

    $category->save(); // Save the category

    return response()->json(['status' => 200, 'message' => 'Category created successfully']);
}

    


    public function edit(Request $request)
    {
        // Find the category
        $category = Category::find($request->id);
        if($category==null){
            return response([
                'status'=>404,
                'message'=>"Category not found with ID =" + $request->id
            ]);
        }else{
            return response([
                'status' =>200,
                'category' =>$category
            ]);
        }

    
        
    }
    

    // List all categories
    public function list(Request $request)
    {
        $categories = Category::all();
        return response()->json(['status' => 200, 'categories' => $categories]);
    }

    // Delete a specific category by ID
    public function destroy(Request $request)
    {
        $category = Category::find($request->id);
    
        if ($category) {
            // Check if the category has an associated image and delete it
            if ($category->image != null) {
                $cateDir = public_path("uploads/category/" . $category->image); // Make sure the path has a trailing slash
                if (File::exists($cateDir)) {
                    File::delete($cateDir); // Delete the image from the directory
                }
            }
    
            // Delete the category after the image is deleted
            $category->delete();
    
            // Return a success response after deletion
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } else {
            // Return an error response if the category is not found
            return response()->json(['error' => 'Category not found'], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        // Validate incoming request
        $request->validate([
            'id' => 'required|exists:categories,id', // Make sure the ID exists in the database
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Check for image file (optional)
        ]);
    
        // Find the category by ID
        $category = Category::find($request->id);
        if (!$category) {
            return response()->json(['status' => 404, 'message' => 'Category not found']);
        }
    
        // Update name and status
        $category->name = $request->name;
        $category->status = $request->status;
    
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image from the server if it exists
            if ($request->old_image && file_exists(public_path('uploads/category/' . $request->old_image))) {
                unlink(public_path('uploads/category/' . $request->old_image));
            }
    
            // Store the new image
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/category'), $imageName);
            $category->image = $imageName;
        } else {
            // Keep the old image if no new image was uploaded
            $category->image = $request->old_image;
        }
    
        // Save the updated category to the database
        $category->save();
    
        // Return success response
        return response()->json(['status' => 200, 'message' => 'Category updated successfully']);
    }
    
    

}
