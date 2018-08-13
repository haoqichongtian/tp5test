<?php

namespace app\api\model;

class Product extends BaseModel
{
    protected $hidden=['delete_time','from','create_time','update_time','pivot'];

    //将表设计的冗余  可以就简单了表的关联 减少了sql的语句从而提高效率
    public function productImg(){
        return $this->belongsTo('image','img_id','id');
    }

    public function getMainImgUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }

    public function getProductProperty(){
        return $this->hasMany('product_property','product_id','id');
    }
    public function getProductImg(){
        return $this->hasMany('product_image','product_id','id');
    }


    //由于查询的主键都是product 所以这种写法不行
    public function getOneCategory(){
        return $this->belongsTo('category','category_id','id');
    }

    public static function getRecentProduct($count){
        $products = self::limit($count)->order('create_time','desc')->select();
        return $products;
//        return self::all([110,112,111]);
// 验证当查找不到数据时返回collection对象 会绕过！判断 必须是查找一组数据，get查询单个数据不行
    }

    /**
     * 获取分类的商品
     * @param $id 分类的id
     */
    public static function getAllInCategory($id){
        $products=self::where('category_id','=',$id)->select();
        return $products;
    }

    /**
     *  增加查询相关表的字段排序的问题
     */
    public static function getDetail($id){
//        $detail = self::with(['getProductProperty','getProductImg.img'])->select($id);
        $detail = self::with([
            'getProductImg' => function($query){
                $query->with(['img'])
                    ->order('order','asc');
            }
        ])
            ->with(['getProductProperty'])
            ->select($id);
        return $detail;
    }

    public static function checkGood($id){
        return self::where('id','=',$id)->find();
    }



}
