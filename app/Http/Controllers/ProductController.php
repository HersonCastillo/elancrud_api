<?php

namespace App\Http\Controllers;

use App\Products;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __contruct(){
        $this->middleware('jwt', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Products list',
            'blob' => Products::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->productValidation($request);
        if(!$validator->fails()){
            try {
                $product = new Products;
                $product->name = $request->name;
                $product->description = $request->description;
                $product->price = $request->price;
                $product->category_id = $request->category_id;
                $product->user_id = auth()->user()->id;
                $product->save();

                return response()->json([
                    'message' => 'Product saved'
                ]);
            } catch(\Exception $ex){
                return response()->json([
                    'message' => 'An error occurred trying save a product',
                    'errors' => [
                        'exception' => [$ex->getMessage()]
                    ]
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Some errors found',
                'errors' => $validator->errors()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Products::find($id);
            return response()->json([
                'message' => "Product with id #$id",
                'blob' => $product
            ]);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'An error occurred trying get a product',
                'errors' => [
                    'exception' => [$ex->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Products::find($id);
        if($product){
            $validator = $this->productValidation($request);
            if(!$validator->fails()){
                try {
                    $product->name = $request->name;
                    $product->description = $request->description;
                    $product->price = $request->price;
                    $product->category_id = $request->category_id;
                    $product->user_id = auth()->user()->id;
                    $product->save();
    
                    return response()->json([
                        'message' => 'Product edited'
                    ]);
                } catch(\Exception $ex){
                    return response()->json([
                        'message' => 'An error occurred trying save a product',
                        'errors' => [
                            'exception' => [$ex->getMessage()]
                        ]
                    ], 500);
                }
            } else {
                return response()->json([
                    'message' => 'Some errors found',
                    'errors' => $validator->errors()
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Category not found',
                'errors' => [
                    'exception' => [$category]
                ]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Products::destroy($id);
            return response()->json([
                'message' => "Product deleted"
            ]);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'An error occurred trying delete a product',
                'errors' => [
                    'exception' => [$ex->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Validation function
     */
    protected function productValidation(Request $request) {
        return Validator::make($request->all(), [
            'name' => 'required|min:3|max:100',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);
    }
}
