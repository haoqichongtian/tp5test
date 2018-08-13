<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/20
 * Time: 17:35
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    public static function getPropertyByID($id){
        return self::where('product_id',$id)->select();
    }
}