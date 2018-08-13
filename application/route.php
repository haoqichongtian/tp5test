<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//1.动态注册式（建议使用） 方法2是配置式编写路由（原本使用的）定义了路由后 原有的Path_Info访问模式失效
//2.Route::rule('路由表达式'，'路由地址','请求类型','路由参数（数组)','变量规则(数组）');
//3. GET POST PUT DELETE *(匹配所有的模式)
//4. 传递的第一个参数可以/ 评价
use think\Route;

Route::rule('thello','index/Test/hello','GET',['https'=>false]);
//Route::rule('thello','index/Test/hello','GET|POST',['https'=>false]);
Route::post('hello/:id','sample/Test/hello');
Route::post('hello2/:id','sample/Test/hello2');
Route::post('hello3/:id','sample/Test/hello3');
Route::post('hello4/:id','sample/Test/hello4');
Route::post('hello5','sample/Test/hello5');
Route::post('hello6/:id','sample/Test/hello6');
Route::post('hello7/:id','sample/Test/hello7');
Route::post('hello8','sample/Test/hello8');
/*
 * 三段式 模块-控制器-方法 而不是目录的路径
 * */
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');

Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

//Route::get('api/:version/product/recent','api/:version.Product/getRecent');
//Route::get('api/:version/product/category/:id','api/:version.Product/getCategoryProduct');
////路由的顺序匹配和 参数的正则匹配
//Route::get('api/:version/product/property/:id','api/:version.Product/getProperty',[],['id' => '\d+']);


/**
 * 路由分组，通过闭包的方式
 * 效率比直接写会提高一部分
 */
Route::group('api/:version/product',function (){
   Route::get('/recent','api/:version.Product/getRecent');
   Route::get('/category/:id','api/:version.Product/getCategoryProduct');
   Route::get('/property/:id','api/:version.Product/getProperty',[],['id' => '\d+']);
});

Route::get('api/:version/category/all','api/:version.Category/getCategory');
Route::get('api/:version/category/:id','api/:version.Category/getOneCategory');

//token 定义为post code的安全性要求
Route::post('api/:version/token/user','api/:version.token/getToken');
Route::post('api/:version/token/verify','api/:version.token/verifyToken');
Route::post('api/:version/token/app','api/:version.token/getAppToken');


Route::post('api/:version/address','api/:version.address/createOrUpdateAddress');
Route::post('api/:version/address/getAddress','api/:version.address/getUserAddress');

Route::post('api/:version/order','api/:version.order/placeOrder');
Route::get('api/:version/order/by_user','api/:version.order/getSummaryByUser');
Route::get('api/:version/order/:id','api/:version.order/getDetail',[],['id'=>'\d+']);
Route::get('api/:version/order/paginate','api/:version.order/getSummary');
Route::put('api/:version/order/delivery','api/:version.order/delivery');


Route::post('api/:version/pay/pre_order','api/:version.pay/getPreOrder');
Route::post('api/:version/pay/notify','api/:version.pay/receiveNotify');
Route::post('api/:version/pay/re_notify','api/:version.pay/redirectNotify');
