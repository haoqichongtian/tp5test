<?php

namespace app\api\model;

class Theme extends BaseModel
{
    protected $hidden = ['delete_time','update_time','topic_img_id','head_img_id'];

    /**
     * 这部分是表的关联关系
     */
    public function topicImg(){
        return $this->belongsTo('image','topic_img_id','id');
    }

    public function headerImg(){
        return $this->belongsTo('image','head_img_id','id');
    }

    public function product(){
        return $this->belongsToMany('product','theme_product','product_id','theme_id');
    }


    /**
     * 下面是接口请求数据
     * 外界调用 需用static
     */
    public static function getTopic($params){
        return self::with(['topicImg','headerImg'])->select($params);
    }

    public static function getThemeInfo($id){
        return self::with(['product','topicImg','headerImg'])->select($id);
    }
}
