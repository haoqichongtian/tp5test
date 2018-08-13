<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/20
 * Time: 11:24
 */

namespace app\api\model;


class User extends BaseModel
{
    public function address(){
        return $this->hasOne('user_address','user_id','id');
    }

    public static function getByOpenId($openid){
        return self::where('openid','=',$openid)->find();
    }
}