<?php
$config['sign_key']     = 'BLPCA5BeDS71gXLvCFd8h6ouYR';
$config['login_expire'] = '1800';// session 30分钟有效期

$config['pctoken_key']    = 'PCBLsfmepl7gDFHDK29opyak';
$config['pctoken_expire'] = 1800;// TOKEN有效期30分钟
$config['access_token'] = 'BL_ATOKEN_';// access_token保存KEY
$config['bl_sm'] = 'BL_SM_';// 保存PC传递的sessionId和memberToken

// H5游戏存放地址
// $config['game_url']     = "http://172.18.194.123/";
$config['game_url']     = "http://game.ibl.cn";

$config['blcoin_rate']  = 1;// 游戏币与人民币比例 1（分人民币）/1（游戏币）
$config['blcoin_point_rate']  = 1;// 游戏币:积分比例 1积分=?游戏币
$config['tax_code']           = '6';// 6%


// 白鹭接入网游配置
$config['bailu_channel_id']     = 21695;
$config['bailu_appkey']         = 'tJPzbf5rRBrNXYoNkHLZn';
$config['bailu_callback_url']   = 'http://www.ibl.cn/dest/recharge_egret.html';// 白鹭道具支付时,调用前端支付页面


// 商户号、密钥(兑换平台)
$config['merid']    = '40161327';// 商户号 测试号：12345678 线上：40161327
$config['secret']   = 'a354a6d123e1d2b98f2bf19c6adfad76';// 商户号密钥   测试：12345678 线上：a354a6d123e1d2b98f2bf19c6adfad76

// 商户号、密钥（支付中台)
$config['p_merid']  = '010090150505150';// 商户号（测试、生产是同一个）
$config['p_secret'] = 'FED8A9737DC75516E1DAC38995F31D45';// 商户号密钥  测试：1B74EC7344C8F1321BE4464551169E27 生产：FED8A9737DC75516E1DAC38995F31D45
$config['p_secret1'] = 'FED8A9737DC75516E1DAC38995F31D45';// PC人民币支付 已丢弃

// 百联OpenApi
$config['bl_appid']     = '4N3H9223G6';// 测试环境: 818c933737454de5a64e273333372764，生产： 4N3H9223G6
$config['bl_secret']    = '99796M3NW6T9KRo4Z7TVz6hbZye809r10';// 测试：3F7yk668BbO36AE73G7O25w82y3727133，生产： 99796M3NW6T9KRo4Z7TVz6hbZye809r10
$config['bl_salt']      = 0;

// 订单模块
$config['order_expire'] = '1800';// 订单有效期(单位s)
$config['order_list']   = '';// 订单列表页面
$config['front_callback']   = '';// 前端充值回调通知地址

// 百联网站头部--域名
if (ENVIRONMENT == 'development') {// 开发环境
    $config['passport_url']     = 'https://passport.st.bl.com/';// 测试地址
    $config['domain_js']        = 'http://res11.st.iblimg.com/';
    $config['domain_img']       = 'http://res11.st.iblimg.com/';
    $config['domains_help']     = 'http://help.st.bl.com';
    $config['domain_channel']   = 'http://channel.st.bl.com';
    $config['domain_global']    = 'http://global.st.bl.com';
    $config['domain_s']         = 'http://s.st.bl.com';
    $config['domain_promotion'] = 'http://promotion.st.bl.com';
    $config['domain_main']      = 'http://www.st.bl.com';
    $config['domain_life']      = 'http://life.st.bl.com';
    $config['domain_fashion']   = 'http://fashion.st.bl.com';
    $config['domain_blk']       = 'http://blk.st.bl.com';
    $config['domain_my']        = 'https://my.st.bl.com';
    $config['domain_passport']  = 'https://passport.st.bl.com';
    $config['domain_jiaofei']   = 'http://jiaofei.st.bl.com';
    $config['domain_chongzhi']  = 'http://chongzhi.st.bl.com';
    $config['domain_chat']      = 'http://chat.st.bl.com';
    $config['domain_cart']      = 'http://cart.st.bl.com';
} elseif(ENVIRONMENT == 'testing') {// 测试环境
    $config['passport_url']     = 'https://passport.ut.bl.com/';// 压测
    $config['domain_js']        = 'http://k10.ut.iblimg.com/';
    $config['domain_img']       = 'http://k10.ut.iblimg.com/';
    $config['domains_help']     = 'http://help.ut.bl.com';
    $config['domain_channel']   = 'http://channel.ut.bl.com';
    $config['domain_global']    = 'http://global.ut.bl.com';
    $config['domain_s']         = 'http://s.ut.bl.com';
    $config['domain_promotion'] = 'http://promotion.ut.bl.com';
    $config['domain_main']      = 'http://www.ut.bl.com';
    $config['domain_life']      = 'http://life.ut.bl.com';
    $config['domain_fashion']   = 'http://fashion.ut.bl.com';
    $config['domain_blk']       = 'http://blk.ut.bl.com';
    $config['domain_my']        = 'http://my.ut.bl.com';
    $config['domain_passport']  = 'https://passport.ut.bl.com';
    $config['domain_jiaofei']   = 'http://jiaofei.ut.bl.com';
    $config['domain_chongzhi']  = 'http://chongzhi.ut.bl.com';
    $config['domain_chat']      = 'http://chat.ut.bl.com';    
    $config['domain_cart']      = 'http://cart.ut.bl.com';
}else { // 生产环境
    $config['passport_url']     = 'https://passport.bl.com/';// 生产
    $config['domain_js']        = 'http://res11.iblimg.com/';
    $config['domain_img']       = 'http://res11.iblimg.com/';
    $config['domains_help']     = 'http://help.bl.com';
    $config['domain_channel']   = 'http://channel.bl.com';
    $config['domain_global']    = 'http://global.bl.com';
    $config['domain_s']         = 'http://s.bl.com';
    $config['domain_promotion'] = 'http://promotion.bl.com';
    $config['domain_main']      = 'http://www.bl.com';
    $config['domain_life']      = 'http://life.bl.com';
    $config['domain_fashion']   = 'http://fashion.bl.com';
    $config['domain_blk']       = 'http://blk.bl.com';
    $config['domain_my']        = 'https://my.bl.com';
    $config['domain_passport']  = 'https://passport.bl.com';
    $config['domain_jiaofei']   = 'http://jiaofei.bl.com';
    $config['domain_chongzhi']  = 'http://chongzhi.bl.com';
    $config['domain_chat']      = 'http://chat.bl.com';
    $config['domain_cart']      = 'http://cart.bl.com';
}