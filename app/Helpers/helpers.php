<?php
   
if (!function_exists('jsonFormat')) {
    function jsonFormat($result, $code, $message, $data){
        return response()->json([
            'result'=>$result,
            'status' => $code,
            'message' => $message,
            'data'=>$data,
        ]);
    }
}