<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * 更改状态
     */
    public function status(Request $request){
      try{
        $param = $request-> all();
        if(!isset($param['id']) || !$param['id']) throw new \Exception('缺少ID');
        if(!isset($param['status']) || !$param['status']) throw new \Exception('缺少分类状态');
        // if(!$param['status'] == '开启' || !$param['status'] == '关闭') throw new \Exception('分类状态不对');
        $category =  Category::find($param['id']);
        if($param['status'] == $category -> status){
          return response()->json(['code' => 200,'msg' => '无更新',]);
        }else{
          $category -> status = $category->status == '关闭'? '开启':'关闭';
          $category -> save();
          return response()->json(['code' => 200,'msg' => '更新成功',]);
        }
        // 清空缓存
        foget_cache_category();
      }catch(\Exception $e){
        return response()->json(['code' => 100,'msg' => $e->getMessage(),]);
      }
    }
    /**
    *分类详情 
    */
    public function show(Request $request){
      try{
        $param = $request->all();
        // return $param;
        $data = Category::find($param['id']);
        return response()->json(['code' => 200,'msg' => '获取成功','data'=>$data]);
      }catch(\Exception $e){
        return response()->json(['code' => 100,'msg' => $e->getMessage()]);
      }
    }
    /**
     * 获取分类列表
     */
    public function index(Request $request){
        try{
            // $param = $request->all();
            // $where = "1";
            // //分页
            // if(isset($param['page']) && $param['page']){
            //     $data = Category::whereRaw($where)
            //       ->orderBy('id','asc')
            //       ->paginate($param['page_num']);
            // }else{
            //     // 'pid',0 或者 level = 1
                
            // }
            $type = $request->input('type');
            if($type == 'all'){
               return cache_category_all();   
            }else{
              return cache_category();
            }
        }catch(\Exception $e){
            return response()->json(['code' => 100,'msg' => $e->getMessage()]);
        }
    }
    /**
     * 添加分类
     */
    public function edit(Request $request){
        try{
          $param = $request->all();
          $validate = Validator::make($param,[
            'name' => 'required|max:16',
          ],[
            "name.required" => "分类名称不能为空",
            "name.max" => "分类名称不能超过16字符",
          ]);
      
          if($validate->fails()) throw new \Exception($validate->errors()->first());
          $pid = $request -> input('pid',0);
           $level = $pid == 0 ? 1 : (Category::find($pid) -> level + 1);
          if($level > 3) throw new \Exception('不能超过三级分类');
          $insertData = [
            'name' => $param['name'],
            'pid' => $pid,
            'level' => $level ,//根据$pid查询 下级分类+1
          ];

          if(isset($param['id']) && $param['id']){
            // 判断 当前id 的name 与传进来的name是否一样；如果一样则无更新；不一样就进行修改
            if(Category::find($param['id']) -> name  == $insertData['name']){
              return response()->json(['code' => 200,'msg' => '无更新','data'=>$param['id']]);
            }else{
              // 先查询出这一条数据 然后进行修改
              Category::find($param['id']) ->update($insertData);
              return response()->json(['code' => 200,'msg' => '修改成功','data'=>$param['id']]);
            }
            
          }else{
            Category::create($insertData);
            return response()->json(['code' => 200,'msg' => '添加成功',]);
          }
          // 清空缓存
          foget_cache_category();
        }catch(\Exception $e){
          return response()->json(['code' => 100,'msg' => $e->getMessage(),]);
        }
      
    }
}
