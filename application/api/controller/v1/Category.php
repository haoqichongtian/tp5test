<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/18
 * Time: 17:37
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    public function getCategory(){
//        $categorys=CategoryModel::getCategoryList();
        $categories=CategoryModel::all([],'img');
        if($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }

    public function getOneCategory($id){
        $categorys=CategoryModel::getCategoryInfo($id);
        if($categorys->isEmpty()){
            throw new CategoryException();
        }
        return $categorys[0];
    }
}