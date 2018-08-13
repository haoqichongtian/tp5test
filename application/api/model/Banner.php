<?php
namespace app\api\model;

class Banner extends BaseModel
{

    public function items(){
        return  $this->hasMany('BannerItem','banner_id','id');
    }
    protected $hidden = ['delete_time','update_time'];

    //定义模型对应的表对象
//    protected $table = 'category';
    public static function getBannerByID($id)
    {
        //方式1：原生sql
//        $result=Db::query('select * from banner_item where banner_id=?',[$id]);
        //方式2：query类查询构造器  select(2维数组)\find（只能返回1条数据（数组））\update\delete\insert前面是 query对象
        // where('字段名'，'表达式','查询条件');表达式法   数组法(有安全问题 不好用) 闭包法
//        $result =Db::table('banner_item')->where('banner_id','=',$id)->select();
        //闭包法
        /*$result = Db::table('banner_item')
//            ->fetchSql() 加上后 会返回sql语句并不执行sql
            ->where(function ($query) use ($id){
                $query->where('banner_id','=',$id);
            })
            ->select();
        return $result;*/

        //方法3 推荐的模型与关联模型的方法

        $banner = self::with(['items','items.imgitems'])->find($id);
        return $banner;
    }
}