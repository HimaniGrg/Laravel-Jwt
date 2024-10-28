<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponseHelper;

class TagController extends Controller
{
    public function index(){
        $tag = Tag::with(['posts'])->get();
        if($tag->count()){
            return ApiResponseHelper::getData($tag);
        }
        else{
            return ApiResponseHelper::getError("No data available", 404);   
        }
        
    }

     public static function show($id){
        $tag = Tag::findOrFail($id);
        return ApiResponseHelper::getData($tag);
     }   
     
}
