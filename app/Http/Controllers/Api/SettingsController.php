<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Slider;
use App\Models\Group;
use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    
     public function index(){
        
        $info = Setting::first();
        
        return response(['status' => 'success', 'info' => $info]);
    }

    public function sliders(Request $request){
         $paginate = $_GET['limit'] ?? null;
         $orderBy = $_GET['orderBy'] ?? null;
         $list = Slider::query();

        //   dd($list->toSql());
          
          if($orderBy!=null){
              $orderBy = explode("_",$orderBy);
              $list = $list->orderBy($orderBy[0],$orderBy[1]);
          }
          
          if($paginate!=null){
              $list = $list->paginate($paginate);
          }else{
              $list = $list->get();
          }
          
         return response(['status' => 'success', 'list' => $list]);
    }
    
    public function faq(Request $request){
         $paginate = $_GET['limit'] ?? null;
         $orderBy = $_GET['orderBy'] ?? null;
         $list = Faq::query();

        //   dd($list->toSql());
          
          if($orderBy!=null){
              $orderBy = explode("_",$orderBy);
              $list = $list->orderBy($orderBy[0],$orderBy[1]);
          }
          
          if($paginate!=null){
              $list = $list->paginate($paginate);
          }else{
              $list = $list->get();
          }
          
         return response(['status' => 'success', 'list' => $list]);
    }
    
    
     public function groups(Request $request){
         $paginate = $_GET['limit'] ?? null;
         $orderBy = $_GET['orderBy'] ?? null;
         $list = Group::with('teacher')->whereIn("id",json_decode($request->user()->group_ids,1));
        //   dd($list->toSql());
          
          if($orderBy!=null){
              $orderBy = explode("_",$orderBy);
              $list = $list->orderBy($orderBy[0],$orderBy[1]);
          }
          
          if($paginate!=null){
              $list = $list->paginate($paginate);
          }else{
              $list = $list->get();
          }
          
         return response(['status' => 'success', 'list' => $list]);
    }
    
   
    
    
}
