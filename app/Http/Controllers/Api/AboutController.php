<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index(Request $request){
        $result = About::first();

        return response(['status'=>'success', 'info'=>$result]);
    }
}
