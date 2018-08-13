<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/17
 * Time: 17:36
 */

namespace app\api\controller\v1;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeMissException;
use think\Request;

class Theme
{
    /**
     * @url /theme
     * @return 返回一组theme模型
     * @throws ThemeMissException
     */
    public function getSimpleList($ids=''){
        (new IDCollection())->goCheck();
        $params=explode(',',$ids);
        $theme=ThemeModel::getTopic($params);
        if(!$theme){
            throw new ThemeMissException();
        }
        return $theme;
    }

    public function getComplexOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $themeInfo = ThemeModel::getThemeInfo($id);
        if($themeInfo->isEmpty()){
            throw new ThemeMissException();
        }
        return $themeInfo[0];
    }
}