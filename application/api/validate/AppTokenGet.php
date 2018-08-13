<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/8/10
 * Time: 15:21
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidata
{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty',
    ];
}