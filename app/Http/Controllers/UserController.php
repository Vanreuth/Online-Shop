<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Display a listing of users
    public function index()
    {
        $users = User::all(); 
        return view('back-end.user', compact('users'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/temp'), $filename,'public');
            return response()->json([
                'status' => 200, 
                'img' => $filename,
            ]);
        }

        return response()->json(['status' => 400, 'message' => 'Failed to upload image']);
    }
    public function cancel(Request $request)
    {
        // Check if the image path is provided
        if ($request->has('image')) {
            // Get the image path from the request (just the filename)
            $imagePath = public_path('uploads/temp/' . $request->img);
    
            // Check if the file exists in the directory
            if (File::exists($imagePath)) {
                // Use unlink() or File::delete() to remove the image
                File::delete($imagePath);
    
                return response()->json(['status' => 200, 'message' => 'Image deleted successfully']);
            } else {
                return response()->json(['status' => 404, 'message' => 'Image not found']);
            }
        }
    
        // Return an error if no image was provided in the request
        return response()->json(['status' => 400, 'message' => 'No image provided']);
    }

    // Show a specific user by ID
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // Store a new user in the database
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image is nullable
        ]);
    
        // Check if image is uploaded and process it
        $filename = null; // Initialize filename as null
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/user'), $filename);
        }
    
        // Create a new user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->role = $request->input('role');
        $user->img = $filename; // Save the image file name (or null if no image was uploaded)
        $user->save();
    
        return response()->json(['status' => 200, 'message' => 'User created successfully']);
    }
    


    public function list(Request $request)
{
    $users = User::all();
    return response()->json(['status' => 200, 'users' => $users]);
}

public function destroy(Request $request)
{
    $user = User::find($request->id); 
        
    if($user == null){
        return response([
           'status' => 404,
           'message' => "User not found with id "+$request->id
        ]);
    }else{
        $user->delete();
        return response([
           'status' => 200,
           'message' => "User deleted successful",
        ]);
    }
    }
}

