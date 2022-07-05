<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    /**
     * 用户列表
     */
    public function getUser(Request $request){
        try{    
            $data = User::all();
            return response()->json([ 
                'code' => 200,
                'msg' =>'获取列表成功',
                'data' => $data,
            ]);
        }catch(\Exception $e){
            return response()->json([ 
                'code' => 100,
                'msg' => $e->getMessage(),
            ]);
        }
        
    }
}
