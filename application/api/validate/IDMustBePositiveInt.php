<?php
namespace app\api\validate;
class IDMustBePositiveInt extends BaseValidata
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'id' => 'id必须是正整数'
    ];
}
