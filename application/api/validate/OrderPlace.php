<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/27
 * Time: 15:22
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidata
{
    protected $rule = [
        'products' => 'require|checkProducts'
    ];

    protected function checkProducts($values){
        if(empty($values)){
            throw new ParameterException([
                'msg' => 'products参数不能为空'
            ]);
        }
        if(!is_array($values)){
            throw new ParameterException([
                'msg' => 'products必须为一个数组'
            ]);
        }

        foreach ($values as $value){
            $this->checkProduct($value);
        }
        return true;
    }
    protected $singerRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];
    protected function checkProduct($value){
        $validate = new BaseValidata($this->singerRule);
        $result = $validate->batch()->check($value);
        if(!$result){
            $e=new ParameterException([
                'msg' =>$validate->error
            ]);
            throw $e;
        }
    }
}