<?php

namespace App\Http\Controllers;

use App\Likes;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Likes list',
            'blob' => Likes::get()
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
        $validator = $this->likeValidation($request);
        if(!$validator->fails()){
            try {
                $like = new Likes;
                $like->product_id = $request->product_id;
                $like->user_id = auth()->use()->id;
                $like->save();

                return response()->json([
                    'message' => 'Like saved'
                ]);
            } catch(\Exception $ex){
                return response()->json([
                    'message' => 'An error occurred trying save a like',
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $like = Likes::destroy($id);
            return response()->json([
                'message' => "Like deleted"
            ]);
        } catch(\Exception $ex){
            return response()->json([
                'message' => 'An error occurred trying delete a like',
                'errors' => [
                    'exception' => [$ex->getMessage()]
                ]
            ], 500);
        }
    }

    /**
     * Validation function
     */
    protected function likeValidation(Request $request) {
        return Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id'
        ]);
    }
}
