<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2018/7/19
 * Time: 16:40
 */
return [
    'app_id' => 'wxf10240d09fdcd9b0',
    'app_secret' => 'e4c9b8bbc4dc6baf42246683880d4de3',
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    'js_code' => '033t0IIV01rLOV1QOKHV0L7NIV0t0II7',
    'grant_type' => '',
    // 微信使用code换取用户openid及session_key的url地址
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?" .
"appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",

    // 微信获取access_token的url地址
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
"grant_type=client_credential&appid=%s&secret=%s",
];