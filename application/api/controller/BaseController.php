<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/27
 * Time: 16:21
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token as TokenService;
class BaseController extends Controller
{
    //用户和管理员都可以
    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }

    //只有用户可以
    protected function checkExclusiveScope(){
        TokenService::needExclusiveScope();
    }


}