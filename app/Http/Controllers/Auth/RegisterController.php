<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    public function store(Request $request){
        // $data = $request->all();
        // DB::table('user') -> insert($data);
        // return $data;
        $user = new User();
        $user -> name = $request->input('name');
        $user -> email = $request->input('email');
        // 需要加密 bcrypt()
        $user -> password =  bcrypt($request->input('password'));
        $user -> save();
        return response()->json([
            'code' => 200,
            'msg' => '注册成功'
        ]);
    }
}
