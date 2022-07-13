<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Goods extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','category_id','goods_name','des','price','stock','cover','is_on','is_recommend','details',];

    public function comments()
    {
        return $this->hasMany(Category::class,'id','category_id');
    }
}
