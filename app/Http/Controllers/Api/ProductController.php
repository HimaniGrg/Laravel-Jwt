<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Models\product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ProductController extends Controller
{
    public function index(){
        $products = product::get();
        if($products->count()>0){
            return ProductResource::collection($products);
        }
        else{
            return response()->json(['message' => 'No record available'],200);
        }
        
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'description'=>'required|string',
            'price'=>'required|integer'

        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),

            ],422);
        }


        $product = product::create([
            'name'=>$request->name,
            'description'=> $request->description,
            'price'=> $request->price

        ]);
        
        return response()->json([
            'message' => 'product created sucessfully',
            'data'=> new ProductResource($product)
        ],200);

    }
    public function show(product $product){

        return response()->json([new ProductResource($product),200]);
    }
    public function update(Request $request, product $product){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'description'=>'required|string',
            'price'=>'required|integer'

        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),

            ],422);
        }


        $product->update([
            'name'=>$request->name,
            'description'=> $request->description,
            'price'=> $request->price

        ]);
        
        return response()->json([
            'message' => 'product updated sucessfully',
            'data'=> new ProductResource($product)
        ],200);
        
    }
    public function destroy(product $product){
        $product->delete();

        return response()->json([
            'message' => 'product deleted sucessfully',
           
        ],200);
        
    }
}
