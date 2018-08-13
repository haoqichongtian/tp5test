<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/18
 * Time: 8:56
 */

namespace app\lib\exception;


class ThemeMissException extends BaseException
{
    public $code = 400;
    public $msg = '指定主题不存在，请检查主题ID';
    public $errorCode = 30000;
}