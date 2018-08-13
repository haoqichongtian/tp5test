<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/17
 * Time: 17:57
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{

    /**
     * @param $value属性值
     * 注意方法名：驼峰命名
     * Url属性名
     * get Attr固定写法
     */
    public function prefixImgUrl($value,$data){
        $imgUrl=$value;
        if($data['from']==1){
            $imgUrl=config('setting.img_prefix').$value;
        }
        return $imgUrl;
    }
}