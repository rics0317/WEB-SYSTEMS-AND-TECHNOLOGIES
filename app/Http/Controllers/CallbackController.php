<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function handleCallback(Request $request)
    {
        $data = $request->all();
        
        return response("SUCCESS");
    }

    public function handleReturn(Request $request)
    {
        $data = $request->all();
        return response("SUCCESS");
    }
}
