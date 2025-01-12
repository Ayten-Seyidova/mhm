<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutApp;
use Illuminate\Http\Request;

class AboutAppController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/aboutApp",
     *      tags={"AboutApp"},
     *      summary="Get AboutApp",
     *      description="Returns api's response",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       )
     *   )
     */
    public function index(Request $request){
        $result = AboutApp::first();

        return response(['status'=>'success', 'info'=>$result]);
    }
}
