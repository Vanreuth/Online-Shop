<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::all();
        return view('back-end.product', compact('product'));
    }

    public function list(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Search functionality
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $products = $query->paginate(10);

        return response()->json([
            'status' => 200,
            'products' => $products->items(),
            'page' => [
                'totalPage' => $products->lastPage(),
                'currentPage' => $products->currentPage(),
            ],
        ]);
    }

    public function upload(Request $request){

        if ($request->hasFile('image')) {
            $files = $request->file('image'); 
            $images = []; 
        
            foreach ($files as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension(); 
                $file->move(public_path('uploads/temp'), $filename);
                $images[] = $filename; 
            }
        
            return response()->json([
                'status' => 200,
                'messages' => 'Image uploaded successfully',
                'image' => $images, 
            ]);
        }

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


    
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
