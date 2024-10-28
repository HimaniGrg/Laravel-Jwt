<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProductController;


Route::apiResource('products',ProductController::class);

Route::apiResource('posts',PostController::class);

Route::apiResource('users',UserController::class);

Route::apiResource('comments',CommentController::class);

Route::apiResource('tags',TagController::class);

Route::get('/tags/{tagId}/posts', [PostController::class, 'getPostsByTag']);

Route::get('/users/{userId}/posts', [PostController::class, 'getPostsByUser']);


// Route::get('/posts', [PostController::class, 'index']); // Fetch all posts with related data
// Route::get('/posts/{id}', [PostController::class, 'show']); // Fetch a single post with related data


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
