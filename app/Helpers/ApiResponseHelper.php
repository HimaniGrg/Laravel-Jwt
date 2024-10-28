<?php

namespace App\Helpers;

class ApiResponseHelper
{
    public static function getData($data)
    {
        return response()->json([
            "status" => "1",
            "statusCode" => 200,
            "message" => "Data Found",
            "data" => $data
        ], 200);
    }

    public static function getError($message, $status) {

        return response()->json([
            "status" => "0",
            "statusCode" => $status,
            "message" => $message,
            "data" => null
        ], status: $status);
    }

    public static function createdResponse($data)
    {
        return response()->json([
            "status" => "1",
            "statusCode" => 201,
            "message" => "Data added sucessfully",
            "data" => $data
        ], 201);
    }

    public static function updatedResponse($data)
    {
        return response()->json([
            "status" => "1",
            "statusCode" => 200,
            "message" => "Data updated sucessfully",
            "data" => $data
        ], 200);
    }

    public static function destroyResponse()
    {
        return response()->json([
            "status" => "1",
            "statusCode" => 200,
            "message" => "Data deleted sucessfully"
        ], 200);
    }

}