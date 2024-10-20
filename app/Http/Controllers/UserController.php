<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
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
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'row' => 'required|integer|in:0,1',
            'img' => 'required|image|max:2048', // Validate the row field
        ]);
        $filePath = $request->file('img')->store('uploads', 'public');
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create a new user with the validated data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'row' => $request->row,
            'img' => $filePath, // Ensure to use the correct field name
        ]);
        if ($user) {

            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } else {
            // Return an error response
            return response()->json(['error' => 'Failed to create user'], 500);
        }
    }

    public function list(Request $request)
{
    $users = User::all();
    return response()->json(['status' => 200, 'users' => $users]);
}

public function destroy(Request $request)
{
    $user = User::find($request->id); 
        $user->delete();
    }
}

