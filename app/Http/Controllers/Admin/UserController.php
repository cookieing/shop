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
            $name = $request->input('name');
            $email = $request->input('email');
            $page = $request->input('page'); 
            // when 当$name 存在的时候查询 或 $email（完全匹配） 存在的时候查询
            $data = User::when($name,function($query) use ($name){
                // 执行查询条件 查询name 模糊查询传进来的参数
                $query -> where('name','like',"%$name%");
            }) ->when($email,function($query) use ($email){
                // 执行查询条件 查询name 完全匹配传进来的参数
                $query -> where('email',$email);
            }) ->paginate($page);


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
