<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/18
 * Time: 10:17
 */

namespace app\api\validate;


class IDCollection extends BaseValidata
{
    protected $rule =[
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids' => 'ids参数必须是以,分割的正整数'
    ];
    protected function checkIDs($value){
        $value = explode(',',$value);
        if(empty($value)){
            return false;
        }
        foreach ($value as $id){
            if(!$this->isPositiveInteger($id,$rule='',$data='',$field='')){
                return false;
            }
        }
        return true;
    }

}