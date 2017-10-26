<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <title></title>
    <script type="text/javascript"
        src="http://k10.ut.iblimg.com//resources/v4.2/unit/jquery-1.8.2.min.js?version="></script>
    <link rel="stylesheet" type="text/css" href="../css/register.css"/>
    <link rel="stylesheet" type="text/css" href="../css/recharge.css"/>
    <link rel="stylesheet" type="text/css" href="../css/personal_center.css"/>
    <link rel="stylesheet" type="text/css" href="../css/non-adult_protect.css"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css"/>
    <link rel="stylesheet" type="text/css" href="../css/slideshow.css"/>
    <link rel="stylesheet" type="text/css" href="/css/category.css"/>
    <link rel="stylesheet" type="text/css" href="/css/detail.css"/>
    <link rel="stylesheet" type="text/css" href="/css/unfound.css"/>
    <link rel="stylesheet" type="text/css" href="http://res11.iblimg.com/respc-1/resources/v4.0/css/base.css?version="/>
    <link rel="stylesheet" type="text/css" href="http://res11.iblimg.com/respc-1/resources/v4.0/widget/header1200/header1200.css?version="/>
    <link rel="stylesheet" type="text/css" href="http://res11.iblimg.com/respc-1/resources/v4.0/widget/nav1200/nav1200.css?version="/>
    <link rel="stylesheet" type="text/css" href="http://res11.iblimg.com/respc-1/resources/v4.0/widget/shownav/shownav.css?version="/>
    <link rel="stylesheet" type="text/css" href="http://res11.iblimg.com/respc-1/resources/v4.0/widget/tools1200/tools1200.css?version="/>
    <link rel="stylesheet" type="text/css" href="http://res11.iblimg.com/respc-1/resources/v4.1/widget/footer1200/footer1200.css?version="/>
    <script>
        bl_mmc = undefined;
        bl_ad = undefined
    </script>
    <!--[if lte  IE 8]>
    <style type="text/css">
        .popup {
            filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#4C000000, endcolorstr=#4C000000) !important;
        }

        .box1, .box4 {
            filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#4CFFFFFF, endcolorstr=#4CFFFFFF) !important;
        }

        .box2 {
            filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#99FFFFFF, endcolorstr=#99FFFFFF) !important;
        }

        .box3 {
            filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#C8FFFFFF, endcolorstr=#C8FFFFFF) !important;
        }

        .box4 > span {
            filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#7FFFFFFF, endcolorstr=#7FFFFFFF) !important;
        }

    </style>
    <![endif]-->
</head>

<body class="w1200 black-nav">
<!-- tools1200.jsp -->
<div class="tools">
    <div class="wrap">
        <div class="tools-left">
            <div class="tools-leftfont"><a href="javascript:void(0);"><span>网站导航</span><i></i></a></div>
            <span class="left-span"></span>
            <div class="divshow left-show">
                <div class="left-show-title"><i></i><span>旗下网站</span></div>
                <div class="left-show-dl">
                    <dl>
                        <dd><a target="_blank" href="http://www.bailiangroup.cn/">百联集团官网</a></dd>
                        <dd><a target="_blank" href="http://www.safepass.cn/">安付宝网</a></dd>
                        <dd><a target="_blank" href="http://www.dyyy.com.cn/">第一医药</a></dd>
                        <dd><a target="_blank" href="https://www.okcard.com/clogin">OK会员卡专区</a></dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="tools-right">
            <ul>
                <li id="user_not_login" class="box">
                    <div>
                        <?php if(!$_SESSION['uuid']): ?>
                        <a href="<?php echo $this->passport->get('passport_url').'toGameCenterLogin.html?type=login'?>"><i></i><span>请登录</span></a>
                        <a id="headerReg" href="https://reg.bl.com/regist.html" class="registered">注册</a>
                        <?php endif; ?>
                    </div>
                    <b></b>
                </li>
                <li style="display: none" class="box-tols" id="user_login_in">
                    <a href="<?php echo $this->passport->get('domain_my'); ?>/ym/nl/toIndex.html" target="_blank">
                        <i>Hi，</i>
                        <span id="member_name"></span>
                    </a>
                    <a href="<?php echo $this->passport->get('passport_url'); ?>/loginDisplay.html?type=logout">退出</a>
                    <b></b>
                </li>
                <li>
                    <div class="tools-leftfont"><a target="_blank" href="<?php echo $this->passport->get('domain_my'); ?>/ym/orderList.html">我的订单</a></div>
                    <b></b></li>
                <li>
                    <div class="tools-leftfont"><a target="_blank" href="<?php echo $this->passport->get('domain_my'); ?>"><span>我的百联</span><i></i></a>
                    </div>
                    <span class="left-span"></span>
                    <b></b>
                    <div class="divshow hdiv">
                        <dl>
                            <dd><a target="_blank" href="<?php echo $this->passport->get('domain_my'); ?>/ym/orderList.html">我的订单</a></dd>
                            <dd><a target="_blank" href="<?php echo $this->passport->get('domain_my'); ?>/ym/commentList.html">我的评价</a></dd>
                            <dd><a target="_blank" href="<?php echo $this->passport->get('domain_my'); ?>/ym/nl/memberPointList.html">我的积分</a></dd>
                            <dd><a target="_blank" href="<?php echo $this->passport->get('domain_my'); ?>/favorite/myFavorite.html">我的收藏</a></dd>
                            <dd><a target="_blank" href="<?php echo $this->passport->get('domain_my'); ?>/center/series/myCoupon.html">我的优惠券</a></dd>
                        </dl>
                    </div>
                </li>
                <li>
                    <div class="tools-leftfont"><a target="_blank"
                                                   href="<?php echo $this->passport->get('domain_jiaofei'); ?>"><span>充值缴费</span><i></i></a></div>
                    <span class="left-span"></span>
                    <b></b>
                    <div class="divshow pay">
                        <dl class="pay-dl">
                            <dd>
                                <div class="pay-title">水电煤缴费</div>
                                <div class="pay-head">
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_jiaofei'); ?>">缴费首页</a>
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_jiaofei'); ?>/syf/pubfeepage.html?type=sf">水费缴费</a>
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_jiaofei'); ?>/syf/pubfeepage.html?type=dl">电费缴费</a>
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_jiaofei'); ?>/syf/pubfeepage.html?type=mq">燃气缴费</a>
                                </div>
                            </dd>
                            <dd>
                                <div class="pay-title">手机充值</div>
                                <div class="pay-head">
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_chongzhi'); ?>/cz/czpage.html">手机充值</a>
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_chongzhi'); ?>/cardll/cardllpage.html">流量充值</a>
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_chongzhi'); ?>/cz/lbczpage.html">更多充值</a>
                                </div>
                            </dd>
                            <dd>
                                <div class="pay-title">其他缴费</div>
                                <div class="pay-head">
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_chongzhi'); ?>/oilcard/oilcardpage.html">加油卡充值</a>
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_jiaofei'); ?>/other/otherfee.html?type=tel">固话/宽带充值</a>
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_jiaofei'); ?>/other/otherfee.html?type=tt">铁通缴费</a>
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_jiaofei'); ?>/other/otherfee.html?type=ds">有线电视缴费</a>
                                </div>
                            </dd>
                            <dd>
                                <div class="pay-title">游戏点卡</div>
                                <div class="pay-head">
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_chongzhi'); ?>/game/gamerechange.html">腾讯Q币充值</a>
                                    <a target="_blank"
                                       href="<?php echo $this->passport->get('domain_chongzhi'); ?>/game/capassgame.html?dsplb=01108&dsphh=364009">盛大点券充值</a>
                                    <a target="_blank" href="<?php echo $this->passport->get('domain_chongzhi'); ?>/game/gameshoplist.html">更多游戏</a>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </li>
                <li>
                    <div class="tools-leftfont"><a href="javascript:void(0);"><span>客户服务</span><i></i></a></div>
                    <span class="left-span"></span>
                    <b></b>
                    <div class="divshow hdiv">
                        <dl>
                            <dd><a target="_blank" href="<?php echo $this->passport->get('domain_chat'); ?>/chat/client/si?skill=2000">在线客服</a></dd>
                            <dd><a target="_blank" href="<?php echo $this->passport->get('domains_help'); ?>/helpCentersv7.html">帮助中心</a></dd>
                        </dl>
                    </div>
                </li>
                <li></li>
                <li>
                    <div class="tools-leftfont"><a target="_blank" href="<?php echo $this->passport->get('domain_cart'); ?>"><span>购物车</span></a></div>
                    <b></b>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- header1200.jsp -->
<div id="search" class="header">
    <div class="wrap">
        <div class="header-font">
            <div class="header-logo">
                <div class="logo">
                    <a href="<?php echo base_url();?>"><img src="/img/logo.png"></a>
                </div>
                <?php if(!$params['no_search']): ?>
                <div class="secondary-logo"></div>
                <?php else: ?>
                <div class="page_title "><?php echo $params['title']; ?></div>
                <?php endif; ?>
            </div>
            <?php if(!$params['no_search']): ?>
            <div class="header-search">
                <div class="header-search-top">
                    <div class="header-search-input">
                        <div class="indiv">
                            <input type="text" id="first_header_search_input" placeholder="请输入游戏名称或关键字" find_data="1" oninput="search_game(this)">
                        </div>
                        <div class="header-input-show hide" id="js_search_game">
                            
                        </div>
                        <div class="header-input-show1">
                            <div class="inpshow">
                                <dl></dl>
                            </div>
                            <div class="intshow-dl">
                                <dl>

                                </dl>
                            </div>
                        </div>
                        <!--
                        <div class="header-input-show2">
                            <dl>

                            </dl>
                        </div> -->
                    </div>
                    <div class="header-search-button">
                        <a href=""><button type="button" class="header-search-button" id="js_do_serarch"></button></a>
                    </div>
                </div>
                <div class="header-search-font">

                </div>
            </div>
            <?php endif; ?>
            <div class="header-phone"><img src="/img/phone.png"/>
                <span><p>400-900-8800</p>
                    <i>cs@bl.com</i>
                </span>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("#js_do_serarch").click(function(){
            var keywords = $("#first_header_search_input").val();
            var url      = main_url+"search/index?keywords="+keywords;
            $(this).parent().attr('href',url);
        }); 
        // 回车事件
        $("#first_header_search_input").keypress(function(e){
            var code = event.keyCode;
            if (13 == code) { 
                 var keywords = $("#first_header_search_input").val();
                 var url      = main_url+"search/index?keywords="+keywords;
                 window.location.href=url;
            } 
        });
        
    });
    
    // 所搜游戏关键字
    function search_game(obj)
    {
        var keywords = $(obj).val();
        if(keywords.match(/^\s*$/)) {
            return;
        }
        if (keywords !== null  ||  keywords !== undefined || keywords !== '') {
            $.post("/search/keywords_list", { keywords: keywords},function (json) {
                var o = JSON.parse(json);
                if (o.code === 0) {
                    popupCreate.fail(o.msg);
                } else {
                    var list = o.list;
                    var html = '';
                    for (var i = 0; i < list.length; i++) {
                        var url     = "/game/detail?id="+list[i]["id"];
                        html    += '<div class="header-input-title"><div class="header-input-title-fl"><a href="'+url+'">'+list[i]["name"]+'</a></div></div>';
                    };
                    // console.log(html);
                    $("#js_search_game").prepend(html);
                    if (html !== '') {
                        $("#js_search_game").removeClass("hide");
                    }
                }
            });
        }
        
    }
</script>