<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * 获取分类列表
     */
    public function index(Request $request){
        try{
            $param = $request->all();
            $where = "1";
            //分页
            if(isset($param['page']) && $param['page']){
                $data = Category::whereRaw($where)
                  ->orderBy('id','asc')
                  ->paginate($param['page_num']);
            }else{
                // 'pid',0 或者 level = 1
                $data = Category::where('pid',0)
                -> select(['id','name','pid','level'])
                ->with(['children:id,pid,name,level','children.children:id,pid,name,level'])
                ->whereRaw($where)->orderBy('id','asc')->get();
            }
            return $data;   
        }catch(\Exception $e){
            return response()->json(['code' => 100,'msg' => $e->getMessage()]);
        }
    }
    /**
     * 添加分类
     */
    public function store(Request $request){
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
          Category::create($insertData);
          return response()->json(['code' => 200,'msg' => '添加成功',]);
        }catch(\Exception $e){
          return response()->json(['code' => 100,'msg' => $e->getMessage(),]);
        }
      
    }
}
