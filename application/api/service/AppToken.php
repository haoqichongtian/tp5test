<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/8/13
 * Time: 9:10
 */

namespace app\api\service;

use app\api\model\ThirdApp as ThirdArrModel;
use app\lib\exception\TokenException;

class AppToken extends Token
{
    public function get($ac,$sc){
        $app = ThirdArrModel::check($ac,$sc);
        if(!$app){
            throw new TokenException([
                'msg' => '授权失败',
                'errorcode' => 10004
            ]);
        }else{
            $scope = $app->scope;
            $uid = $app->id;
            $value = [
                'scope' => $scope,
                'uid' => $uid
            ];
            $token = self::saveToCache($value);
            return $token;
        }
    }
}