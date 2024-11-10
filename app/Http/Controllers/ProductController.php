<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'price' => 'required|numeric',
            'qty'  => 'required|numeric',

        ]);

        if($validator->passes()){
            //save Product to table in db
            $product = new Product();
            $product->name = $request->name;
            $product->des = $request->des;
            $product->price = $request->price;
            $product->qty  = $request->qty;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->color   = implode(",",$request->color);  
            //[4,3,2] => "4,3,2"
            $product->user_id = Auth::user()->id;
            $product->status = $request->status;

            $product->save();

            if (is_array($request->color)) {
                $product->color = implode(",", $request->color);
            } else {
                // Handle the case where $request->color is not an array (you can set it to an empty string or handle it as needed)
                $product->color = "";
            }
            //Save to images table in db
            if($request->image_uploads != null){
                $images = $request->image_uploads;
                foreach($images as $img){
                    $image = new ProductImg();
                    $image->image  = $img;
                    $image->product_id = $product->id;

                    //move image to product directory 
                    if(File::exists(public_path("uploads/temp/$img"))){

                         //copy
                         File::copy(public_path("uploads/temp/$img"),public_path("uploads/product/$img"));

                         //delete from temp directory
                         File::delete(public_path("uploads/temp/$img"));
 
                    }

                    $image->save();
                }
            }

            
            return response([
                'status' => 200,
                'message' => "Product created successfully",
            ]);

        }else{
            return response()->json([
                'status' => 500,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ]);
        }
    }
    

    public function data(Request $request){
        $category = Category::orderBy('id', 'DESC')->get();
        $brand = Brand::orderBy('id', 'DESC')->get();
        $color = Color::orderBy('id', 'DESC')->get();
        $relatedProducts = Product::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Data received successfully',
            'category' => $category,
            'brand' => $brand,
            'color' => $color,
            'relatedProducts' => $relatedProducts,
        ]);






    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(Request $request)
    {
        $product = Product::find($request->id);
        $productImg = ProductImg::where('product_id',$request->id)->get();
        $brands  = Brand::orderBy('id','DESC')->get();
        $categories = Category::orderBy('id','DESC')->get();
        $colors  = Color::orderBy('id','DESC')->get();

        return response([
            'status' => 200,
            'data'  => [
                'product' => $product,
                'productImages' => $productImg,
                'brands' => $brands,
                'categories' => $categories,
                'colors' => $colors,
            ]
        ]);
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
