<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(){

        $categories = Category::orderBy("id","DESC")->get();
        return view('back-end.brand',compact('categories'));
        
    }

    public function list(Request $request)
    {
        $query = Brand::with('category');

        // Search functionality
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $brand = $query->paginate(10);

        return response()->json([
            'status' => 200,
            'brand' => $brand->items(),
            'page' => [
                'totalPage' => $brand->lastPage(),
                'currentPage' => $brand->currentPage(),
            ],
        ]);
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:brands,name'
        ]);

        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->category_id = $request->category;
            $brand->status = $request->status;
            $brand->save();

            return response([
                'status' => 200,
                'message' => "Brand created successful"
            ]);

        }else{
            return response()->json([
                'status' => 500,
                'error' => $validator->errors(),
            ]);
        }
    }

    public function destroy(Request $request){
        $brand = Brand::find($request->id);

        //checking brand not found
        if($brand == null){
            return response([
               'status' => 404,
               'message' => "Brand not found with id "+$request->id
            ]);
        }else{
            $brand->delete();
            return response([
               'status' => 200,
               'message' => "Brand deleted successful",
            ]);
        }

        
    }

    public function edit(Request $request)
    {
        $brand = Brand::find($request->id);

        if (!$brand) {
            return response()->json([
                'status' => 404,
                'message' => 'Brand not found'
            ]);
        }

        return response()->json([
            'status' => 200,
            'brand' => $brand
        ]);
    }

    // Update brand
    public function update(Request $request)
{
    $brand = Brand::find($request->id);  // Find brand by ID

    if ($brand) {
        $brand->name = $request->name;
        $brand->category_id = $request->category;
        $brand->status = $request->status;
        $brand->save();

        return response()->json([
            'status' => 200,
            'message' => 'Brand updated successfully',
        ]);
    } else {
        return response()->json([
            'status' => 404,
            'message' => 'Brand not found',
        ]);
    }
}

}
