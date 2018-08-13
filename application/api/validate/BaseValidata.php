<?php
namespace app\api\validate;
use app\lib\exception\ParameterException;
use think\Validate;
use think\Request;
use think\Exception;
class BaseValidata extends Validate
{
    public function goCheck()
    {
        //获取http传入的变量并校验
        $request=Request::instance();
        $params= $request -> param();
        $result=$this->batch()->check($params);
        //引入异常后的处理改写
        if(!$result){
            //面对对象的思想 传入改变通过构造函数
            $e=new ParameterException([
                'msg' =>$this->error
            ]);

            throw $e;
        }
        else{
            return true;
        }
    }

    public function getDateByRule($arrays){
        //不允许用户传递user_id或者uid 因为用户id应该是从token的缓存中获取
        if(array_key_exists('user_id',$arrays)|array_key_exists('uid',$arrays)){
            throw new ParameterException([
                'msg' => '参数中包含非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

    protected function isPositiveInteger($value,$rule='',$data='',$field='')
    {
        if(is_numeric($value) && is_int($value+0) && ($value + 0) > 0){
            return true;
        }else{
            return false;
        }
    }

    protected function isNotEmpty($value,$rule='',$data='',$field=''){
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }

    protected function isMobile($value,$rule='',$data='',$field=''){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if($result){
            return true;
        }else{
            return false;
        }
    }
}