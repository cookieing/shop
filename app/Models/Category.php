<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','pid','level'];

    /**
     * 分类的子类
     * 关联
     */
     public function children(){
        // 外键pid 内键id
        return $this->hasMany(Category::class,'pid','id');
     }
}
