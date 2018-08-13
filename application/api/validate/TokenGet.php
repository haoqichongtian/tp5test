<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/19
 * Time: 11:15
 */

namespace app\api\validate;


class TokenGet extends BaseValidata
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];
    protected $message = [
        'code' => 'code没有，怕不是做梦哦'
    ];
}