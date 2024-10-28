<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    
    public function index(){
        $comment = Comment::with(['users', 'posts'])->get();
        if($comment->count()){
            return ApiResponseHelper::getData($comment);
        }
        else{
            return ApiResponseHelper::getError("No data available", 404);   
        }
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'body' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id'
        ]);
        if ($validator->fails()){
            return ApiResponseHelper::getError($validator->messages(),422);
        }

        $comment = Comment::create($request->only(['body', 'user_id', 'post_id']));

        return ApiResponseHelper::createdResponse($comment);
    }   

    // View a single comment
    public function show($id)
    {
        $comment = Comment::findOrFail($id);
        return ApiResponseHelper::getData($comment);
    }

    // Update an existing comment
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        
        $validator = Validator::make($request->all(),[
            'body' => 'required|string',
           
        ]);
        if ($validator->fails()){
            return ApiResponseHelper::getError($validator->messages(),422);
        }

        $comment->update([
            'body' => $request->body
        ]);

        return ApiResponseHelper::updatedResponse($comment);
    }

    // Delete a comment
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return ApiResponseHelper::destroyResponse();
        }
}


