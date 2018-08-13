<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/18
 * Time: 15:15
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\model\ProductProperty as ProductPropertyModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product
{
    public function getRecent($count=15){
        (new Count())->goCheck();
        $recentList = ProductModel::getRecentProduct($count);
        if($recentList->isEmpty()){
            throw new ProductException();
        }
        //临时的隐藏字段 将一组数据视作某个对象下的数据 操作这个对象从而操作这组数据  数据集
//        $collection = collection($recentList);
        $products=$recentList->hidden(['summary']);
        return $products;
    }

    /**
     * @param $id 分类id
     */
    public function getCategoryProduct($id){
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getAllInCategory($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    public function getProperty($id){
        (new IDMustBePositiveInt())->goCheck();
        $property = ProductModel::getDetail($id);
        if($property->isEmpty()){
            throw new ProductException();
        }
        return $property[0];
    }
}