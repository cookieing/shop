<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OssController extends Controller
{
    /**
     * 生成OSS token
     */
    public function token(){
        try{
            
           
        }catch(\Exception $e){
            return response()->json(['code' => 100,'msg' => $e->getMessage(),]);
        }
    }
}
