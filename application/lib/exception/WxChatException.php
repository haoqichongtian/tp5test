<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/20
 * Time: 9:15
 */

namespace app\lib\exception;


class WxChatException extends BaseException
{
    public $code = 400;
    public $msg = '微信服务器接口调用失败';
    public $errorCode = 999;
}