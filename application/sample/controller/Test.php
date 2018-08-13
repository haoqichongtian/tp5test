<?php

namespace app\sample\controller;

use think\Request;
class Test
{
    //第一种参数获取（参数不能少，参数少了会报错）
    public function hello($id,$name,$age){
        echo $id;
        echo '|';
        echo $name;
        echo '|';
        echo $age;
//         return 'hello world';
    }
    //第二种Request 获取
    public function hello2(){
        //这种方法不区分请求类型：get post put Request::instance()是实例
        $id = Request::instance()->param('id');
        $name = Request::instance()->param('name');
        $age = Request::instance()->param('age');

        echo $id;
        echo '|';
        echo $name; 
        echo '|';
        echo $age;
        // return 'hello world';
    }
    public function hello3(){
        //这种方法不区分请求类型：get post put 
        $all = Request::instance()->param();
//        $routeparam = Request::instance()->route();获取路径中的参数
//        $routeparam = Request::instance()->get();获取路径？中的参数
//        $routeparam = Request::instance()->post();获取body中的参数
        var_dump($all);
        // return 'hello world';
    }
    //依赖注入 实例化的过程系统自己完成
    public function hello31(Request $request){
        //这种方法不区分请求类型：get post put 
        $all = $request->param();
        var_dump($all);
        // return 'hello world';
    }
    public function hello4(){
        //这种方法不区分请求类型：get post put delete
        $getparam = Request::instance()->get();//获取拼接
        $routerparam = Request::instance()->route();//获取：id
        $postparam = Request::instance()->post();//获取body
        var_dump($getparam,$routerparam,$postparam);
        // return 'hello world';
    }

    //助手函数的获取参数
    public function hello5()
    {
        $all=input('param.');
        var_dump($all);
    }
    public function hello6()
    {
        $age=input('param.age');
        $name=input('get.name');
        $age2=input('post.age');
        //包含所有参数的age 会被post.age覆盖
        $id=input('route.id');
        var_dump($age,$name,$age2,$id);
    }
    //依赖注入
    public function hello7(Request $request)
    {
        $all=$request->param();
        var_dump($all);
    }

    //依赖注入
    public function hello8()
    {
//        $str=substr("'xixix'", 0, -1);
//        $str=substr($str, 1);
//        echo $str;
       echo str_replace('"',"",'"xixix"');
    }
}
