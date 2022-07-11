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
    public function index(Request $request){
        try{    
          $param = $request->all();
          if(isset($param['id']) && $param['id']){
            $data = User::where('id',$param['id']) -> first();
          }else {
            $where = "1";
            //模糊查询 name 
            if(isset($param['name']) && $param['name']){
              $where .= " AND name like '%{$param['name']}%'";
            }
            //模糊查询 email
            if(isset($param['email']) && $param['email']){
              $where .= " AND email like '%{$param['email']}%'";
            }
            //分页
            if(isset($param['page']) && $param['page']){
              $data = User::whereRaw($where)
                ->orderBy('id','asc')
                ->paginate($param['page_num']);
            }else{
              $data = User::whereRaw($where)
                ->orderBy('id','asc')
                ->get();
            }
          }
      
      
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
    /**
     * 更新用户状态
     */
    public function lock(Request $request){
        $praam = $request->all();
        if(isset($praam['id']) && $praam['id']){
          $data = User::find($praam['id']);
          $data -> is_locked = $data -> is_locked == '否' ? '是' : '否';
          $data -> save();
          return response() ->json(['code' => 200,'msg' => '更新成功',]);
        }else{
          return response() ->json(['code' => 100,'msg' => '更新失败',]);
        }
      
      }
}
