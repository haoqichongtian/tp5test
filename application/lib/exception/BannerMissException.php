<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/16
 * Time: 10:36
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code=400;
    public $msg='没有对应的数据';
    public $errorCode=40000;
}