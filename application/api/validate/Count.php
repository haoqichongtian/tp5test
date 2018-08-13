<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/18
 * Time: 15:26
 */

namespace app\api\validate;


class Count extends BaseValidata
{
    protected $message = [
        'count' => '传入的count必须是（1-15）的正整数'
    ];
    protected $rule = [
      'count' => 'isPositiveInteger|between:1,15'
    ];

}