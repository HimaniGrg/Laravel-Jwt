<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponseHelper;

class PostController extends Controller
{
 
    public function index(){
        $post = Post::with(['user:id,name', 'comments:id,post_id,body', 'tags:name'])->select('id', 'title', 'body','user_id')->get();
        if($post->count()){
            return ApiResponseHelper::getData($post);
        }
        else{
            return ApiResponseHelper::getError("No data available", 404);   
        }
        
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'title'=>'required|string|max:255',
            'body'=>'required|string',
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id'=>'required|exists:users,id',
            'tags' => 'array', // Optional array of tag IDs
            'tags.*' => 'exists:tags,id', // Validate that each tag ID exists

        ]);

        if ($validator->fails()){
            return ApiResponseHelper::getError($validator->messages(),422);
        }
    
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Store image
            
        }

        // Create a new Post using create() method
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user_id,
            'image' => $imagePath // Include the image path if uploaded
        ]);
        // Sync tags if provided
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

       return ApiResponseHelper::createdResponse($post->load('tags'));
        
    }

    public function show($id)
    {
        // $post = Post::with(['users', 'comments', 'tags'])->findOrFail($id);
        $post = Post::with(['user:id,name', 'comments:user_id,post_id,body', 'tags:name'])->select('id', 'title', 'body','user_id')->findOrFail($id);

        return ApiResponseHelper::getData($post);
    }

    public static function update(Request $request, Post $post){

        $validator = Validator::make($request->all(),[
            'title'=>'required|string|max:255',
            'body'=>'required|string',
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id'=>'required|exists:users,id'

        ]);

        if ($validator->fails()){
            return ApiResponseHelper::getError($validator->messages(),422);
        }
    
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Save the uploaded images in storage/app/public/images
            
        }
    
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user_id,
            'image' => $imagePath // Include the image path if uploaded
        ]);

        // Sync tags if provided
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return ApiResponseHelper::updatedResponse($post->load('tags'));


    }

    public static function destroy(Post $post){
        $post->delete();

       return ApiResponseHelper::destroyResponse();

    }

    public function getPostsByTag($tagId)
    {
        // Find the tag by ID, or return a 404 error if not found
        $tag = Tag::findOrFail($tagId);

        // Retrieve posts associated with the tag
    
        $posts = $tag->posts()
             ->with(['user:id,name', 'comments:id,post_id,body', 'tags:id,name'])
             ->select('posts.id', 'posts.title', 'posts.body', 'posts.user_id')
             ->get();


        return ApiResponseHelper::getData([
            'tag' => $tag,
            'posts' => $posts
        ]);
    }

    public function getPostsByUser($userId)
    {
        // Retrieve the user along with their posts, comments, and tags
        $user = User::with(['posts.comments:id,post_id,body', 'posts.tags:name'])->findOrFail($userId);
        

        return ApiResponseHelper::getData([
            'user_id' => $userId,
            'posts' => $user->posts
        ]);
    }

}
