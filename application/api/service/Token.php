<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/20
 * Time: 14:31
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken(){
        //32个字符组成一组随机字符串
        $randomChars=getRandChar(32);
        //用3组字符串，进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME'];
        //salt
        $salt = config('secure.token_salt');

        return md5($randomChars.$timestamp.$salt);
    }

    /**
     * 写个通用的方法 根据参数来获取缓存中的某一个变量（wxResult[openid,session_key],uid,scope)
     *
     */
    public static function getCurrentTokenVar($key){
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){//redis 会直接读成数组
                $vars = json_decode($vars,true);//转化为数组
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的token变量并不存在');
            }
        }
    }


    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    public static function getCurrentScope(){
        $scope = self::getCurrentTokenVar('scope');
        return $scope;
    }

    //用户和管理员都可以访问
    public static function needPrimaryScope(){
        $scope = self::getCurrentScope();
        if($scope){
            if($scope>=ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }
    //只有用户才能访问的权限
    public static function needExclusiveScope(){
        $scope = self::getCurrentScope();
        if($scope){
            if($scope==ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    //检测 订单和用户是否匹配
    public static function isValidOperate($checkUID){
        if(!$checkUID){
            throw new Exception('必须传入一个被检测的uid');
        }
        $currentOperateUID = self::getCurrentUid();
        if($checkUID == $currentOperateUID){
            return true;
        }
        return false;
    }
    //验证token
    public static function verifyToken($token){
        $exist = Cache::get($token);
        if($exist){
            return true;
        }else{
            return false;
        }
    }


    //写入缓存
    public static function saveToCache($cacheValue){
        $key = self::generateToken();
        $value = json_encode($cacheValue); //json_encode 数组转json字符串
        $expire_in = config('setting.token_expire_in');
        //写入缓存 文件系统（可配置redis moment_cache）
        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }
}