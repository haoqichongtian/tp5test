<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/30
 * Time: 17:28
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id','delete_time'];
    protected $autoWriteTimestamp = true;

    public function Products(){
       return $this->hasMany('order_product','order_id','id');
    }

    public function getSnapItemsAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }


    public static function getSummaryByUser($uid,$page,$size){
        //返回的是Pagenation对象
        $pagedate = self::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($size,true,['page' => $page]);
        return $pagedate;
    }

    public static function getSummaryByPage($page,$size){
        //返回的是Pagenation对象
        $pagedate = self::order('create_time desc')
            ->paginate($size,true,['page' => $page]);
        return $pagedate;
    }

    public static function getProducts($id){
        //返回的是contructor对象
       $result = self::with(['Products'])->select($id);
       return $result;
    }
}