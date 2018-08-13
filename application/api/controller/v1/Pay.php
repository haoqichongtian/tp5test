<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/31
 * Time: 15:01
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;
use think\Loader;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getpreorder']
    ];

    public function getPreOrder($id = ''){
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }

    //处理微信的支付结果的回调
    public function redirectNotify(){
        //WxNotify 自定义的微信回调处理类
        $notify = new WxNotify();
        $config = new \WxPayConfig();
        $notify->Handle($config);
    }

    public function receiveNotify(){
        //微信回调的频率15 30 180 1800 3600 单位秒
        //检测库存量 超卖
        //更新订单status状态
        //减少库存
        //成功处理 返回给微信成功处理的信息 否则返回没有成功处理
        //特点 post 返回xml格式，不允许附带参数？路径中的查询参数

//        $notify = new WxNotify();
//        $config = new \WxPayConfig();
//        $notify->Handle($config);

        $xmlDate = file_get_contents('php://input');
        $result = curl_post_raw('http://tp5.com/api/v1/pay/re_notify?XDEBUG_SESSION_START=14778',$xmlDate);

        //转发后若不返回给微信的处理结果 微信会不停的发请求
        //实际上线还原不转发即可
    }
}