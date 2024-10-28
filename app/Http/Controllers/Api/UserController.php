<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Resources\UserResource;

class UserController extends Controller
{
    
    public function index(){
        $user = User::with(['posts','comments'])->get();
        if($user->count()){
            return ApiResponseHelper::getData($user);
        }
        else{
            return ApiResponseHelper::getError("No users available:",404);
        }
        
    }

    public function show($id)
    {
        $post = User::with(['posts','comments'])->findOrFail($id);

        return ApiResponseHelper::getData($post);
    }

}
