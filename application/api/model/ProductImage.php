<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/20
 * Time: 17:54
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $visible = ['img','order'];
    public function img(){
        return $this->belongsTo('image','img_id','id');
    }
}