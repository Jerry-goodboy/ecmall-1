<?php

return array(
    'code'      => 'wechatpay',
    'name'      => Lang::get('wechatpay'),
    'desc'      => Lang::get('wechatpay_desc'),
    'is_online' => '1',
    'author'    => 'fengshuo',
    'website'   => 'https://pay.weixin.qq.com/',
    'version'   => '1.0',
    'currency'  => Lang::get('wechatpay_currency'),
    'config'    => array(
        'wechatpay_appid'   => array(        //账号
            'text'  => Lang::get('wechatpay_appid'),
            'desc'  => '微信支付appid',
            'type'  => 'text',
        ),
        'wechatpay_appsecret'       => array(        //密钥
            'text'  => Lang::get('wechatpay_appsecret'),
            'desc'  => '微信支付appsecret',
            'type'  => 'text',
        ),
    ),
);

?>