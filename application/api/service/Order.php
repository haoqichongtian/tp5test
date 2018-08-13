<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/27
 * Time: 15:27
 */

namespace app\api\service;


use app\api\model\Product;
use app\api\model\UserAddress;
use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct as OrderProductModel;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use Exception;
use think\Db;

class Order
{
    //客户端传递的 订单的商品列表
    protected $oProducts;
    //根据id从数据库中查询的商品信息 以便于和oproduct进行比较
    protected $products;

    protected $uid;

    public function place($uid,$oProducts){
        $this->oProducts = $oProducts;
        $this->uid = $uid;
        $this->products = $this->getProductByOrder($oProducts);
        $status = $this->gerOrderStatus();
        if(!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }
        //创建订单的快照
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    //创建订单的方法
    private function createOrder($orderSnap){
        Db::startTrans();
        try{
            $orderNo = $this->makeOrderNo();
            $order =new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $orderSnap['totalPrice'];
            $order->snap_img = $orderSnap['img'];
            $order->snap_name = $orderSnap['name'];
            $order->total_count = $orderSnap['totalCount'];
            $order->snap_items = json_encode($orderSnap['pStatus']);
            $order->snap_address = $orderSnap['snapAddress'];

            //订单写入数据库
            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;

            //注意给数组增加字段 需要&
            foreach ($this->oProducts as &$p){
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProductModel();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        }catch (Exception $ex){
            Db::rollback();
            throw $ex;
        }

    }

    public static function makeOrderNo(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    //生成订单的快照
    private function snapOrder($status){
        $snap = [
            'totalPrice' => 0,
            'totalCount' => 0,
            'name' => '',
            'img' => '',
            'pStatus' => [],
            'snapAddress' => ''
        ];

        $snap['totalPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['name'] = $this->products[0]['name'];
        if(count($this->products)>1){
            $snap['name'] .='等';
        }

        $snap['img'] = $this->products[0]['main_img_url'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] =json_encode($this->getAddress());

        return $snap;
    }

    //获取订单的地址
    private function getAddress(){
        $address = UserAddress::where('user_id','=',$this->uid)->find();
        if(!$address){
            throw new UserException([
                'msg' => '用户收货地址不存在',
                'errorCode' => 60001
            ]);
        }
        $address = $address->toArray();
        return $address;

    }

    //获取订单的状态
    private function gerOrderStatus(){
        $status = [
            'pass' => true,
            'orderPrice' => 0,//订单价格总和
            'totalCount' => 0,//订单的数量总和
            'pStatusArray' => [] //订单详细信息
        ];

        foreach ($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus($oProduct['product_id'],$oProduct['count'],$this->products);
            if(!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['counts'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    //为了检查库存的复用写的方法(主要查出传递的订单product和数据库中的product)
    public function checkOrderStock($orderID){
        $payOProducts = OrderProductModel::where('order_id','=',$orderID)->select();
        $this->oProducts = $payOProducts;
        $this->products = $this->getProductByOrder($payOProducts);
        $status = $this->gerOrderStatus();
        return $status;
    }

    //获取某一产品的状态
    private function getProductStatus($oPid,$oCount,$products){
        $pStatus = [
            'id' => null,
            'haveStock' => true,
            'counts' => 0,
            'name' => '',
            'totalPrice' =>0,
            'price' =>0,
        ];

        $pIndex = -1;
        //找到对比的产品在products中的位置
        for($i=0;$i<count($products);$i++){
            if($oPid == $products[$i]['id']){
                $pIndex = $i;
            }
        }
            if($pIndex == -1){
                throw new OrderException([
                    'msg' => 'id为'.$oPid.'的商品无法找到'
                ]);
            }

            $pStatus['id'] = $oPid;
            $pStatus['name'] = $products[$pIndex]['name'];
            $pStatus['haveStock'] = $oCount<$products[$pIndex]['stock']?true:false;
            $pStatus['totalPrice'] = $products[$pIndex]['price']*$oCount;
            $pStatus['price'] = $products[$pIndex]['price'];
            $pStatus['main_img_url'] = $products[$pIndex]['main_img_url'];
            $pStatus['counts'] = $oCount;

        return $pStatus;

    }

    //根据订单信息查找真实的product信息
    private function getProductByOrder($oProduct){
        $oPIDs = [];
        foreach ($oProduct as $item){
            array_push($oPIDs,$item['product_id']);
        }
        $products = Product::all($oPIDs)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();
        return $products;
    }

    //发送模板消息
    public function delivery($id){
        (new IDMustBePositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id);
        if($success){
            return new SuccessMessage();
        }
    }
}