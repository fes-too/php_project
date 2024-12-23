<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $products = Product::get();
        if($products->count() > 0){
        
        return ProductResource::collection($products);
        
        }else{
        
        return response()->json(['message' => 'No record available'],200);

        }
    }

    public function show(Product $product){
        return new ProductResource($product);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[

            'name' => 'required|string|max:255',
            'description' => 'required',
            'price'=> 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'All fields are mandatory.',
                'error' => $validator->messages()], 422);
        }

        
       $product = Product::create([
            'name'=> $request->name,
            'description' => $request->description,
            'price'=> $request->price
        ]);

        return response()->json(['message'=> 'Product Created successfully',
         'data' => new ProductResource($product)],200);
    }

    public function update(Product $product, Request $request){

        $validator = Validator::make($request->all(),[

            'name' => 'required|string|max:255',
            'description' => 'required',
            'price'=> 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'All fields are mandatory.',
                'error' => $validator->messages()], 422);
        }

        $product->update([
            'name'=> $request->name,
            'description' => $request->description,
            'price'=> $request->price
        ]);

        return response()->json(['message'=> 'Product Updated successfully',
         'data' => new ProductResource($product)],200);

    }

    public function destroy(Product $product){

        $product->delete();
        return response()->json(['message'=> 
        'Product Deleted successfully',],200);
         

        
    }


}
