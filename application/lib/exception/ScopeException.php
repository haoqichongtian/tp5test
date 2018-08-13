<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/26
 * Time: 18:01
 */

namespace app\lib\exception;


class ScopeException extends BaseException
{
    public $code = 400; //一般表示资源状态更新成功
    public $msg = '权限不足';
    public $errorCode = 10003;
}