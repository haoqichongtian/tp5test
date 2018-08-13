<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/26
 * Time: 15:31
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 201; //一般表示资源状态更新成功
    public $msg = 'ok';
    public $errorCode = 0;
}