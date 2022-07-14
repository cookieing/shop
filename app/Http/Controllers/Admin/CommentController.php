<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * 获取评论列表
     */
    public function index(Request $request){
        try{
            $param = $request->all();
            // 根据id查询详情
            if(isset($param['id']) && $param['id']){
                $data = Comment::find($param['id']);
            }else{
                $where = '1';
                // 分页
                if(isset($param['page']) && $param['page']){
                    $data = Comment::whereRaw($where) -> orderBy('id','asc')->paginate($param['page_num']);
                }else{
                    $data = Comment::whereRaw($where) -> orderBy('id','asc')->get();
                }
            }
            return response()->json(['code' => 200,'msg' => '获取成功','data' =>$data]);
        }catch(\Exception $e){
            return response()->json(['code' => 100,'msg' => $e->getMessage(),]);
        }
    }
    /**
     * 添加评论列表
     */
    public function edit(Request $request){
        try{
            $param = $request->all();
            $user_id = auth('api')->id();
            $param['user_id'] = $user_id;
            $validate = Validator::make($param,[
                'goods_id' => 'required',
                'rate' => 'required',
                'content' => 'required',
            ],[
                "goods_id.required" => "商品id不能为空",
                "rate.required" => "评价级别不能为空",
                "content.required" => "评价内容不能为空",
            ]);
            if($validate->fails()) throw new \Exception($validate->errors()->first());
            if(isset($param['id']) && $param['id']){
                Comment::find($param['id']) ->update($param);
                return response()->json(['code' => 200,'msg' => '更新成功',]);
            }else{
                Comment::create($param);
                return response()->json(['code' => 200,'msg' => '添加成功',]);
            }
        }catch(\Exception $e){
            return response()->json(['code' => 100,'msg' => $e->getMessage(),]);
        }
    }
}
