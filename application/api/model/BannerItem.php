<?php

namespace app\api\model;

use think\Model;

class BannerItem extends Model
{
    protected $visible = ['key_word','type','imgitems'];
    //banner_item 和image的关联关系
    public function imgitems(){
        return $this->belongsTo('Image','img_id','id');
    }
}
