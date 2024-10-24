<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    // List all colors
    public function index()
    {
        $color = Color::all(); 
        return view('back-end.color', compact('color'));
    }

    // Get colors with pagination and search
    public function list(Request $request)
    {
        $query = Color::query();

        // Search functionality
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $colors = $query->paginate(5);

        return response()->json([
            'status' => 200,
            'colors' => $colors->items(),
            'page' => [
                'totalPage' => $colors->lastPage(),
                'currentPage' => $colors->currentPage(),
            ],
        ]);
    }

    // Store a new color
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:colors,name',
            'code' => 'required',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->passes()) {
            Color::create($request->all());

            return response()->json([
                'status' => 200,
                'message' => "Color created successfully"
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'error' => $validator->errors(),
            ]);
        }
    }

    // Edit a color
    public function edit(Request $request)
    {
        $color = Color::find($request->id);

        if (!$color) {
            return response()->json([
                'status' => 404,
                'message' => 'Color not found'
            ]);
        }

        return response()->json([
            'status' => 200,
            'color' => $color
        ]);
    }

    // Update a color
    public function update(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'status' => 'required|in:0,1', // assuming status is a boolean or enum type (active/inactive)
        ]);
    
        // Find the color by ID
        $color = Color::find($request->id);
    
        if ($color) {
            // Update the color with the validated data
            $color->update([
                'name' => $request->name,
                'code' => $request->code,
                'status' => $request->status,
            ]);
    
            return response()->json([
                'status' => 200,
                'message' => 'Color updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Color not found',
            ]);
        }
    }
    

    // Delete a color
    public function destroy(Request $request)
    {
        $color = Color::find($request->id);

        if ($color) {
            $color->delete();
            return response()->json([
                'status' => 200,
                'message' => "Color deleted successfully",
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Color not found with id " . $request->id
            ]);
        }
    }
}
