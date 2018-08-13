<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/26
 * Time: 10:51
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    public static function getAddByUid($uid){
        return self::where('user_id','=',$uid)->find();
    }
}