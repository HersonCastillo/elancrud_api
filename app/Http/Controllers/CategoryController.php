<?php

namespace App\Http\Controllers;

use App\Categories;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Categories list',
            'blob' => Categories::get()
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
        $validator = $this->categoryValidation($request);
        if(!$validator->fails()){
            try {
                $category = new Categories;
                $category->name = $request->name;
                $category->description = $request->description;
                $category->save();

                return response()->json([
                    'message' => 'Category saved'
                ]);
            } catch(\Exception $ex){
                return response()->json([
                    'message' => 'An error occurred trying save a category',
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
            $category = Categories::find($id);
            return response()->json([
                'message' => "Category with id #$id",
                'blob' => $category
            ]);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'An error occurred trying get a category',
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
        $category = Categories::find($id);
        if($category){
            $validator = $this->categoryValidation($request);
            if(!$validator->fails()){
                try {
                    $category->name = $request->name;
                    $category->description = $request->description;
                    $category->save();
    
                    return response()->json([
                        'message' => 'Category edited'
                    ]);
                } catch(\Exception $ex){
                    return response()->json([
                        'message' => 'An error occurred trying save a category',
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
            $category = Categories::destroy($id);
            return response()->json([
                'message' => "Category deleted"
            ]);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'An error occurred trying delete a category',
                'errors' => [
                    'exception' => [$ex->getMessage()]
                ]
            ], 500);
        }
    }

    protected function categoryValidation(Request $request) {
        return Validator::make($request->all(), [
            'name' => 'required|min:3|max:100',
            'description' => 'required'
        ]);
    }
}
