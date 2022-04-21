<?php

namespace App\Http\Controllers\Api;

use App\Constants\SessionMessage;
use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    public function error($message_title = null,$item = null, $data= NULL,$code = 422)
    {
        $session_message = new SessionMessage();
        return response()->json([
            "type" =>"error",
            "message" => $session_message->messages($message_title, $item),
            "data"=> $data
        ],$code);
    }
    // Method called for success
    public function success($message_title=null,$item = null, $data=NULL, $code = 200)
    {
        $session_message = new SessionMessage();
        return response()->json([
            "type" => "success",
            "message" => $session_message->messages($message_title, $item),
            "data"=> $data
        ]);
    }

}
