<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/26
 * Time: 10:54
 */

namespace app\api\validate;


class AddressNew extends BaseValidata
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
//        'mobile' => 'require|isMobile',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty'
    ];
}