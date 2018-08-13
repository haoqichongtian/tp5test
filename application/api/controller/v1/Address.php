<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/26
 * Time: 10:51
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\ScopeException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Controller;
use think\Request;
use app\api\model\UserAddress as UserAddressModel;
use app\api\model\User as UserModel;
class Address extends BaseController
{

    //前置执行放置在前面 在xx之前 小写方法名
    protected $beforeActionList  = [
        'checkPrimaryScope' => ['only' => 'createorupdateaddress'],
    ];



    public function createOrUpdateAddress(){
        $validate =new AddressNew();
        $validate->goCheck();
        // 根据token获取用户uid
        // 根据uid查找用户数据，不存在抛出异常
        // 获取用户从客户端提交来的地址信息
        // 根据用户信息是否存在 增加或更新地址
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }
//        这部分提取出来为方法
//        $params =Request::instance()->param();
        $dataArray = $validate->getDateByRule(input('post.'));
//        $address = UserAddressModel::getAddByUid($uid); 两种方法都可以
        //下面的关联模型的方法
        $address = $user->address;
        if(!$address){
            //简单方法 model创建
//            $address = UserAddressModel::create($dataArray);
            // 用模型的关联模型（$user->address()）方法来创建
            $user->address()->save($dataArray);
        }else{
            //更新地址数据
            $user->address->save($dataArray);
        }

        //rest规则 改变了资源的状态 则返回模型数据
//        return $user;
        return json(new SuccessMessage(),201);
    }

    public function getUserAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddressModel::where('user_id',$uid)->find();
        if(!$userAddress){
            throw new UserException([
                'msg'=>'用户地址不存在',
                'errorCode' => 60001
            ]);
        }

        return $userAddress;
    }

}