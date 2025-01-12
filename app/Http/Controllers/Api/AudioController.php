<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutApp;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    public function download(Request $request, $fileId)
    {

        $downloadUrl = "https://drive.google.com/uc?export=download&id=$fileId";
        header("Location: $downloadUrl");
        exit();

    }
}
