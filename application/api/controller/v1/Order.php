<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/27
 * Time: 14:49
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Controller;
use app\api\model\Order as OrderModel;
use think\Request;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
class Order extends BaseController
{
    /**
     * 订单的支付的流程
     * 1.用户选择商品传递商品信息
     * 2.检测库存 库存有则存入订单的数据库，返回客户端，下单成功
     * 3.调用支付接口
     * 4.检测库存
     * 5.服务器调用微信的支付接口
     * 6.检测库存
     * 7.支付成功，减少库存
     */

    /**
     * 1.库存检查
     * 2.创建订单
     * 3.减库存--预扣除
     * 4.if pay 真正的减库存
     * 5.一定时间没支付，还原库存
     *
     * linux crontab
     *
     * 任务队列
     * 创建订单 把任务加入任务队列，到任务了就执行回调
     * redis(缓存没支付就执行函数)
     *
     */
    protected $beforeActionList  = [
        'checkExclusiveScope' => ['only' => 'placeorder'],
        'checkPrimaryScope' => ['only' => 'getSummaryByUser'],
        'checkPrimaryScope' => ['only' => 'getDetail']
    ];
    //下单
    public function placeOrder(){
        $validate = new OrderPlace();
        $validate->goCheck();
        $products = input('post.products/a');//获取数组加a
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid,$products);
        return $status;
    }

    //获取历史订单
    public function getSummaryByUser($page = 1,$size = 2){
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pageDate =OrderModel::getSummaryByUser($uid,$page,$size);
        if($pageDate->isEmpty()){
            return [
                'data' => [],
                'current_page' =>$pageDate->getCurrentPage()
            ];
        }
        $date = $pageDate->hidden(['snap_items','snap_address','prepay_id'])
            ->toArray();
        return [
            'data' => $date['data'],
            'current_page' =>$pageDate->getCurrentPage()
        ];
    }
    //获取订单详情
    public function getDetail($id){
        (new IDMustBePositiveInt())->goCheck();
//        $orderDetail = OrderModel::getProducts($id);
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }


    //获取cms需要的所有订单
    public function getSummary($page = 1,$size = 10){
        (new PagingParameter())->goCheck();
        $pageDate =OrderModel::getSummaryByPage($page,$size);
        if($pageDate->isEmpty()){
            return [
                'data' => [],
                'current_page' =>$pageDate->getCurrentPage()
            ];
        }
        $date = $pageDate->hidden(['snap_items','snap_address','prepay_id'])
            ->toArray();
        return [
            'data' => $date['data'],
            'current_page' =>$pageDate->getCurrentPage()
        ];
    }
}