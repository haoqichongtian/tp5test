<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/8/1
 * Time: 17:38
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Log;

Loader::import('WxPay.Wxpay',EXTEND_PATH,'.Api.php');
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Notify.php');
class WxNotify extends \WxPayNotify
{

/*<xml>
    <appid>wx2421b1c4370ec43b</appid>
    <attach>支付测试</attach>
    <body>JSAPI支付测试</body>
    <mch_id>10000100</mch_id>
    <detail><![CDATA[{ "goods_detail":[ { "goods_id":"iphone6s_16G", "wxpay_goods_id":"1001", "goods_name":"iPhone6s 16G", "quantity":1, "price":528800, "goods_category":"123456", "body":"苹果手机" }, { "goods_id":"iphone6s_32G", "wxpay_goods_id":"1002", "goods_name":"iPhone6s 32G", "quantity":1, "price":608800, "goods_category":"123789", "body":"苹果手机" } ] }]]></detail>
       <nonce_str>1add1a30ac87aa2db72f57a2375d8fec</nonce_str>
       <notify_url>http://wxpay.wxutil.com/pub_v2/pay/notify.v2.php</notify_url>
       <openid>oUpF8uMuAJO_M2pxb1Q9zNjWeS6o</openid>
       <out_trade_no>1415659990</out_trade_no>
       <spbill_create_ip>14.23.150.211</spbill_create_ip>
       <total_fee>1</total_fee>
       <trade_type>JSAPI</trade_type>
       <sign>0CB01533B8C1EF103065174F50BCA001</sign>
</xml>*/
    //处理微信通知
    public function NotifyProcess($objData, $config, &$msg)
    {
        //$objData 已经从xml转化为数组了
        if($objData['result_code'] == 'SUCCESS'){
            $orderNo = $objData['out_trade_no'];
            Db::startTrans();
            try{
                $order = OrderModel::where('order_no','=',$orderNo)
//                    ->lock(true)
                    ->find();
                if($order->status==1){
                    $service = new OrderService();
                    //检查库存
                    $stockStatus = $service->checkOrderStock($order->id);
                    if($stockStatus['pass']){
                        $this->updataOrderStatus($order->id,true);
                        $this->reduceStock($stockStatus);
                    }else{
                        $this->updataOrderStatus($order->id,false);
                    }
                }
                Db::commit();
                return true;
            }catch (Exception $ex){
                Db::rollback();
                Log::error($ex);
                return false;
            }
        }
    }

    private function updataOrderStatus($orderID,$success){
        $status = $success?OrderStatusEnum::PAID:OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id','=',$orderID)->updata(['status'=>$status]);
    }

    private function reduceStock($stockStatus){
        foreach ($stockStatus['pStatusArray'] as $singlePStatus){
            Product::where('id','=',$singlePStatus['id'])->setDec('stock',$singlePStatus['count']);
        }
    }
}