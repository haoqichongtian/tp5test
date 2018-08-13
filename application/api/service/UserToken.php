<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/19
 * Time: 11:19
 */

namespace app\api\service;


use app\lib\exception\TokenException;
use app\lib\exception\WxChatException;
use think\Exception;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code= $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);

    }

    public function get(){
        $result = curl_get($this->wxLoginUrl);
        //json_decode true转为数组，false转为对象
        $wxResult = json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
               return $this->grantToken($wxResult);
            }
        }
    }

    private function grantToken($wxResult){
        /**
         * 取openID
         * 数据库查询此openID,存在即不处理，不存在新增user记录
         * 生成令牌，准备缓存数据，写入缓存
         * 返回令牌到客户端
         */
        $openID = $wxResult['openid'];
        $user = UserModel::getByOpenId($openID);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openID);
        }
        $cacheValue = $this->prepareValue($wxResult,$uid);
        $token = self::saveToCache($cacheValue);
        return $token;
    }



    // 准备key对应的value
    public function prepareValue($wxResult,$uid){
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        //代表App用户的权限
        $cacheValue['scope'] = ScopeEnum::User;
        return $cacheValue;
    }

    //插入一个新的用户
    private function newUser($openid){
        $user = UserModel::create([
            'openid' => $openid,
        ]);
        return $user->id;
    }

    //异常封装后 方便扩展
    private function processLoginError($wxResult){
        throw new WxChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}