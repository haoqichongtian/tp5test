<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/27
 * Time: 13:58
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10001;
}