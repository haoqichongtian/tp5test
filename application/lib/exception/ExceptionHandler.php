<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/16
 * Time: 10:36
 */

namespace app\lib\exception;

use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    public function render(\Exception $e){
        if($e instanceof BaseException){
            $this->code=$e->code;
            $this->msg=$e->msg;
            $this->errorCode=$e->errorCode;

        }else{
//            Config::get('app_debug');
            if(config('app_debug')){
                return parent::render($e);
            }else{
                $this->code=500;
                $this->msg='服务器内部错误，不告诉你';
                $this->errorCode=999;
                $this->recordErrorLog($e);
            }
        }
        $request=Request::instance();

        $result=[
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result,$this->code);
    }

    private function recordErrorLog(\Exception $e){
        Log::init([
            // 日志记录方式，内置 file socket 支持扩展
            'type'  => 'File',
            // 日志保存目录
            'path'  => LOG_PATH,
            // 日志记录级别
            'level' => ['sql'],
        ]);
        Log::record($e->getMessage(),'error');

    }
}