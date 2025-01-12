<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('user.index');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile("upload")) {
            $originname = $request->file("upload")->getClientOriginalName();
            $filename = pathinfo($originname, PATHINFO_FILENAME);
            $extension = $request->file("upload")->getClientOriginalExtension();
            if ($extension == 'png' || $extension == 'PNG' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'svg' || $extension == 'gif' || $extension == 'pdf' || $extension == 'doc' || $extension == 'docx' || $extension == 'xls' || $extension == 'xlsx') {
                $filename = $filename . "_" . time() . "." . $extension;

                $request->file("upload")->move(public_path("CkImage"), $filename);

                $CKEditorfuncNum = $request->input("CKEditorFuncNum");
                $url = asset("CkImage/" . $filename);
                $msg = "Succesfully";
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorfuncNum,'$url','$msg')</script>";

                header("Content-type: text/html;charset-utf-8");
                echo $response;
            }
        }
    }
}
