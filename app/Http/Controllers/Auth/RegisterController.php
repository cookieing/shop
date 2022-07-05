<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function store(Request $request){
        try{
            $param = $request->all();
            $validate = Validator::make($param,[
                'email' => 'required',
                'password' => 'required',
                'name' => 'required',
            ],[
                "email.required" => "邮箱不能为空",
                "password.required" => "密码不能为空",
                "name.required" => "昵称不能为空",
            ]);

            if($validate->fails()) throw new \Exception($validate->errors()->first());



            $user = new User();
            $user -> name = $request->input('name');
            $user -> email = $request->input('email');
            // 需要加密 bcrypt()
            $user -> password =  $request->input('password');
            $user -> save();
            return response()->json([
                'code' => 200,
                'msg' => '注册成功'
            ]);
        }catch(\Exception $e){
            return response()->json([ 
                'code' => 201,
                'msg' => $e->getMessage(),
                // 'lien' => $e->getLine()
            ]);
        }
        
    }
}
