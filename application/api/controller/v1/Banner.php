<?php
    namespace app\api\controller\v1;

    use app\lib\exception\BannerMissException;
    use think\Exception;
    use think\Validate;
    use app\api\controller\TestValidate;
    use app\api\validate\IDMustBePositiveInt;
    use app\api\model\Banner as BannerModel;
    use app\api\model\BannerItem;

    class Banner
    {
        /**
         * 获取指定id的banner信息
         * @http GET
         * @url /banner/:id
         * @id 指明banner的id号
         */
        public function getBanner($id)
        {

            (new IDMustBePositiveInt())->goCheck();
            //方法1 (隐藏调用的方式)
            $banner=BannerModel::getBannerByID($id);
//            $banner->hidden(['update_time','delete_time','items.update_time']);
//            $banner->visible(['items','items.imgitems']);
//            $data = $banner->toArray();
//            unset($data['delete_time']);
            //方法2 静态的调用方式(推荐的使用)  单条：get find /一组：all select  DB不能使用get all  Model最好使用find select;
//            $banner=BannerModel::with(['items','items.imgitems'])->select($id);//模型返回的是对象
            //方法3 实例化的调用方法（主要用来调用模型的自定义方法） 1条记录调用1条记录 不适合面对对象的思维
//            $banner =new BannerModel();
//            $banner = $banner::get($id);
            //            return json($banner);
            if(!$banner){
                throw new BannerMissException();
//                throw new Exception('内部错误');
            }
            return $banner;

        }
        
        public function getBanner2($id)
        {
            $data=[
                'id' => $id
            ];
            //独立验证
            $validata = new IDMustBePositiveInt();

            $result = $validata->check($data);


        }

        public function getBanner3()
        {
            $data=[
                'name' => 'vendor111111',
                'email' => 'vendorqq.com'
            ];
            //自带验证器
            $validata = new Validate([
                'name' => 'require|max:10',
                'email' => 'email'
            ]);

            $result = $validata->batch()->check($data);
            var_dump($validata->getError());
//            var_dump($result);
        }
    }