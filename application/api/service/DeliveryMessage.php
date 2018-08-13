<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/8/13
 * Time: 10:42
 */

namespace app\api\service;


use app\lib\exception\OrderException;

class DeliveryMessage extends WxMessage
{
    const DELIVERY_MSG_ID = 'your template message id';//模板库中的某一模板的id

    public function sendDeliveryMessage($order,$tplJumPage=''){
        if(!$order){
            throw new OrderException();
        }
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumPage;
        $this->prepareMessageDate($order);
        $this->emphasisKeyWord = 'keyword2.DATA';
        return parent::sendMessage($this->getUserOpenID($order->user_id));
    }

    private function prepareMessageDate($order){
        $dt = new \DateTime();
        $data = [
            'keyword1' => [
                'value' => '顺风速运',
            ],
            'keyword2' => [
                'value' => $order->snap_name,
                'color' => '#27408B'
            ],
            'keyword3' => [
                'value' => $order->order_no
            ],
            'keyword4' => [
                'value' => $dt->format("Y-m-d H:i")
            ]
        ];
        $this->data = $data;
    }

    private function getUserOpenID($uid)
    {
        $user = User::get($uid);
        if (!$user) {
            throw new UserException();
        }
        return $user->openid;
    }
}