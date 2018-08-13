<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/18
 * Time: 17:38
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden=['topic_img_id','delete_time','update_time'];

    public function products(){
        return $this->hasMany('product','category_id','id');
    }
    public function img(){
        return $this->belongsTo('image','topic_img_id','id');
    }

    public static function getCategoryList(){
        return self::select();
    }

    public static function getCategoryInfo($id){
        return self::with(['products','img'])->select($id);
    }
}