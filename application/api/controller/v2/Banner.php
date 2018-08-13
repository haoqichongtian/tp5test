<?php
    namespace app\api\controller\v2;


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
            return 'This is V2 version';
        }
        

    }