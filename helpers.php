<?php

use App\Models\Category;

/**
 * 所有分类
 */
if(!function_exists('categoryTree')){
    function categoryTree($status = false){
        $data = Category::select(['id','name','pid','level','status'])
            ->where('pid',0)
            ->when($status !== false,function($query) use ($status){
                $query -> where('status',$status);
            })
            ->with([
                'children'=> function($query) use ($status){
                    $query ->select(['id','name','pid','level','status'])
                    ->when($status !== false,function($query) use ($status){
                        $query -> where('status',$status);
                    });   
                },
                'children.children' => function($query) use ($status){
                    $query ->select(['id','name','pid','level','status'])
                    ->when($status !== false,function($query) use ($status){
                        $query -> where('status',$status);
                    });   
                }
            ])
            ->orderBy('id','asc')->get();
        return $data;
    }
}

/**
 * 缓存没被禁用的分类
 */
if(!function_exists('cache_category')){
    function cache_category(){
        return cache()-> rememberForever('cache_category',function(){
            return categoryTree('开启');
        });
    }
}


 /**
 * 缓存所有的分类
 */
if(!function_exists('cache_category_all')){
    function cache_category_all(){
        return cache()-> rememberForever('cache_category_all',function(){
            return categoryTree();
        });
    }
}
 
/**
 * 添加的时候缓存没办法更新
 * 需要把缓存清除掉
 * 清空分类缓存
 */
if(!function_exists('foget_cache_category')){
    function foget_cache_category(){
        cache() -> forget('cache_category');
        cache() -> forget('cache_category_all');
    }
}