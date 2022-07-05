<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function getUser(Request $request){
        try{    
            $data = User::all();
            return $data;
        }catch(\Exception $e){
            return response()->json([ 
                'code' => 100,
                'msg' => $e->getMessage(),
            ]);
        }
        
    }
}
