<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/8/13
 * Time: 9:13
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
    public static function check($ac,$sc){
        $user = self::where('app_id','=',$ac)->where('app_secret','=',$sc)->find();
        return $user;
    }
}