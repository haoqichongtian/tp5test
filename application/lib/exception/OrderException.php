<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/30
 * Time: 14:25
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单错误';
    public $errorCode = 80000;
}