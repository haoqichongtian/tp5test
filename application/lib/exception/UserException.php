<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/26
 * Time: 15:04
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 400;
    public $msg = '当前的用户不存在';
    public $errorCode = 60000;
}