<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    public function inCategories(){
        return $this -> hasOne(Category::class,'id','category_id');
    }
    /**
     * 商品查询
     */
    public function index(Request $request){
        try{
            $param = $request->all();
            // 根据id查询详情
            if(isset($param['id']) && $param['id']){
                $data = Goods::find($param['id']);
            }else{
                $where = '1';
                // 查询
                if(isset($param['goods_name']) && $param['goods_name']){
                    $where .= " AND goods.goods_name like '%{$param['goods_name']}%'";
                }
                // 分页
                if(isset($param['page']) && $param['page']){
                    $data = Goods::orderBy('id','asc')->paginate($param['page_num']);
                }else{
                    $data = Goods::whereRaw($where) 
                        ->with(['comments'=>function($query) {return $query;}])
                    // ->leftJoin('categories', 'goods.category_id', '=', 'categories.id')
                    // ->select('goods.*')
                    -> orderBy('goods.id','asc')->get();
                }
            }
            return response()->json(['code' => 200,'msg' => '获取成功','data'=>$data]);
        }catch(\Exception $e){
            return response()->json(['code' => 100,'msg' => $e->getMessage()]);
        }
    }
    /**
     * 商品添加
     */
    public function edit(Request $request){
        try{
            $param = $request->all();
            // 对分类进行检查 只能使用3级分类 并且分类不能被禁用
            $catagory = Category::find($param['category_id']);
            if(!$catagory) throw new \Exception('分类不存在');
            if($catagory->status == '关闭') throw new \Exception('分类被禁用');
            if($catagory->level != 3) throw new \Exception('只能三级分类添加商品');

            $user_id = auth('api')->id();
            $param['user_id'] = $user_id;
          
            $validate = Validator::make($param,[
                'category_id' => 'required',
                'goods_name' => 'required',
                'des' => 'required|max:255',
                'price' => 'required|min:0',
                'stock' => 'required|min:0',
                'cover' => 'required',
                // 'pics' => 'required',
                'details' => 'required',
            ],[
                "category_id.required" => "分类id不能为空",
                "price.required" => "价格不能为空",
                "goods_name.required" => "商品名称不能为空",
                "stock.required" => "库存不能为空",
                "cover.required" => "封面图不能为空",
                // "pics.required" => "小图集不能为空",
                "details.required" => "详情不能为空",
            ]);
            if($validate->fails()) throw new \Exception($validate->errors()->first());
            if(isset($param['id']) && $param['id']){
                Goods::find($param['id']) ->update($param);
                return response()->json(['code' => 200,'msg' => '更新成功','data'=>$param['id']]);
            }else{
                Goods::create($param);
                return response()->json(['code' => 200,'msg' => '添加成功',]);
            }
           
        }catch(\Exception $e){
            return response()->json(['code' => 100,'msg' => $e->getMessage(),]);
        }
    }
}
