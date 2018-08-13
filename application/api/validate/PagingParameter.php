<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/8/2
 * Time: 16:23
 */

namespace app\api\validate;


class PagingParameter extends BaseValidata
{
    protected $rule = [
      'page' => 'isPositiveInteger',
      'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'page' => '分页参数page必须为正整数',
        'size' => '分页参数size必须为正整数'
    ];
}