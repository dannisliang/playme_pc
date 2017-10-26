<?php $this->load->view('public/header'); ?>

<!------------todo------------>
<!---banner--->
<div id="banner" class="banner">
    <div id="slideshow1" class="slideshow">
        <?php foreach($active_list as $key=>$val):?>
        <div class="slideshow__item" style="background-image: url(<?=$this->passport->get('game_url').$val["top_img"]?>);"><a  style=" width: 100%;height: 100%;display: block;" href="<?php  echo 'game/'.$val['click_url'] ?>"></a></div>
        <?php endforeach;?>
    </div>

    <div class="login">
        <div id="slideshow2" class="slideshow" attr_data = "<?php echo $_SESSION['uuid']; ?>"></div>
        
        <?php if($_SESSION['uuid']): ?>
        <!--登录成功界面-->
        <div id="login_success">
            <div class="info box1">
                <img src="<?php if ($u_info['image'] && substr($u_info['image'],7) == 'http://') { echo $u_info['image'];} else { echo 'img/temp/userpic.png';} ?>"/>
                <div><?php  echo $u_info['name'] ?></div>
                <div><i class="blb-icon">&nbsp;</i><span>游戏币：<b><?php  echo $u_info['blcoin'] ?></b><span><a target="_blank" href="pay/index">充值</a></span></span>
                </div>
            </div>
            <div class="info box2">
                <span><a href="user/user_center">我的账户</a></span>
                <span id="message"><a href="user/news">消息</a><b><?php echo $u_info['news_num'] ?></b></span>
                <span id="collect"><a href="user/user_center">我的收藏</a><b><?php echo $u_info['favorite_num'] ?></b></span>
<!--                <span id="cancel"><a href="user/logoff?channel=1&uuid=15511&sign=1">注销</a></span>-->
            </div>
        </div>
        <?php else: ?>
        <!--未登录界面-->
        <div class="login info box4" id="login">
            <span>
                请先<a href="<?php echo $this->passport->get('passport_url').'toGameCenterLogin.html?type=login'?>">登录</a>或<a target="_blank" href="https://reg.bl.com/regist.html">免费注册</a>
            </span>
        </div>
        <?php endif; ?>
        <div class="info box3">
            <a target="_blank" href="/other/noadult_protect">
                <img src="img/jg.png"/>
                <div>
                    <span>未成年人</span>
                    <span>家长监护工程</span>
                </div>
            </a>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<!-----hot------>
<div class="hot">
    <div class="hot-box">
        <div class="title">
            <i style=" background:url(img/wy.png) no-repeat;">&nbsp;</i>
            <h4>热门网游
                <span>
                    <i>&nbsp;</i>
                    <a href="game/online_list">更多>></a>
                </span>
            </h4>
        </div>
        <div class="content">
            <?php foreach($online_list as $key=>$val):?>
            <span  class="<?php if($key == 0 || $key == 4) echo 'first'; ?> <?php if($key == 3 || $key == 7) echo 'last'; ?> ">
                <a href="<?php echo 'game/detail?id='.$val['id']; ?>">
                  <img src="<?php echo $val['g_icon'] ?>"/>
                  <span><b><?php echo $val['name'] ?></b><i><?php echo $val['keys'] ?></i></span>
                  <div><span>马上去玩</span></div>
              </a>
            </span>
            <?php endforeach;?>
        </div>
    </div>
</div>

<!---欢乐小游戏--->
<div class="happy">
    <div class="title"><i style=" background:url(img/happy.png) no-repeat;">&nbsp;</i>
        <h4>
            欢乐小游戏
            <span>
            <i>&nbsp;</i>
            <a href="game/common_list">更多>></a>
        </span>
        </h4>
    </div>
    <?php foreach ($common_list as $key=>$val): ?>
    <div class="list <?php if($key === 0 || $key === 8) echo 'first'?>  <?php if($key === 7 || $key === 15) echo 'last'?> ">
        <a href="<?php echo "game/detail?id=".$val['id']; ?>">
            <img src="<?php echo $val['icon'] ?>"/>
            <div><?php echo $val['name'] ?></div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!----热门兑换----->
<div class="convert">
    <div class="convert-box">
        <div class="title"><i style=" background:url(img/fire.png) no-repeat;">&nbsp;</i>
            <h4>
                热门兑换
                <div id="convert-tab">
                    <span class="active" tab="convert_tab1">话费</span>
                    <span tab="convert_tab2">流量</span>
                </div>
            </h4>
        </div>
        <!---花费兑换----->
        <div class="slide-box" id="convert_tab1">
            <div class="slide-content">
                <div class="slide-scroll">
                    <?php foreach ($exchange_list['mobile_bill'] as $k=>$v):  ?>
                    <?php if($k%4 == 0):?><div class="slide-list"><?php endif;?>
                        <div class="first popup-open" popup-id="<?php if(!$v['close']) echo 'exchange_popup'; ?>"
                             exchange-data='{"id":<?php echo $v['id']?>,"img":"<?php echo $v['img']?>","title":"<?php echo $v['name'] ?>","num":<?php echo $v['exchange_num'] ?>,"desc":"<?php echo $v['info'] ?>","coin":<?php echo $v['blcoin'] ?>,"mycoin":"<?php echo $u_info['blcoin'] ?>"}'>
                            <span><img src="<?php echo $v['img']?>"/><span><i
                                    class="blb-icon"><?php echo $v['blcoin'] ?></i><span>立即兑换</span></span></span>
                            <?php if($v['close']): ?>
                            <div>
                                <span>已售罄</span>
                            </div>
                            <?php endif;?>
                        </div>
                    <?php if(($k+1)%4 == 0 || $k == count($exchange_list['mobile_bill'])-1):?></div><?php endif;?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="left-btn"></div>
            <div class="right-btn"></div>
            <div class="dot"></div>
        </div>
        
        <!---流量兑换----->
        <div class="slide-box" id="convert_tab2" style="display: none;">
            <div class="slide-content">
                <div class="slide-scroll">
                    <?php foreach ($exchange_list['mobile_flow'] as $key=>$val):  ?>
                    <?php if($key%4 == 0):?><div class="slide-list"><?php endif;?>
                        <div class="first popup-open" popup-id="<?php if(!$v['close']) echo 'exchange_popup'; ?>"
                            exchange-data='{"id":"<?php echo $v['id']?>","img":"<?php echo $val['img']?>","title":"<?php echo $v['name'] ?>","num":<?php echo $v['exchange_num'] ?>,"desc":"<?php echo $v['info'] ?>","coin":<?php echo $v['blcoin'] ?>,"mycoin":"<?php echo $u_info['blcoin'] ?>"}'>
                            <span><img src="<?php echo $val['img']?>"/><span><i
                                    class="blb-icon"><?php echo $val['blcoin'] ?></i><span>立即兑换</span></span></span>
                            <?php if($v['close']): ?>
                            <div>
                                <span>已售罄</span>
                            </div>
                            <?php endif;?>
                        </div>
                     <?php if(($key+1)%4 == 0 || $key == count($exchange_list['mobile_flow'])-1):?></div><?php endif;?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="left-btn"></div>
            <div class="right-btn"></div>
            <div class="dot"></div>
        </div>
    </div>
</div>

<!--------兑换弹窗------->
<div class="exchange_pop popup public_pop" id="exchange_popup">
    <div class="popup-box public_box exchange_box" id="exchange_box">
        <div class="popup-close"></div>
        <div class="color_div">
            <div class="div1 pup_color"></div>
            <div class="div2 pup_color"></div>
            <div class="div3 pup_color"></div>
            <div class="div4 pup_color"></div>
        </div>
        <div class="top_div layer0top" id="layer0top">
            <img src="img/2.png"/>
            <div class="float_div">
                <span><img src="img/blb-icon.png"/><span>3000</span></span>
            </div>
            <div class="word_div">
                <span class="title1">上海电信话费10元</span>
                <p class="p1">已兑换：<span>1720</span>份</p>
                <span class="explain">【兑换说明】</span>
                <p class="desc"></p>
            </div>
        </div>
        <div class="bottom_div layer0bottom" id="layer0bottom">
            <i>我的游戏币：<b>21801</b></i>
            <span class="now_btn popup-open right-span" popup-id="layer1_popup">立即兑换</span>
        </div>
    </div>
</div>

<!--------是否兑换------->
<div class="layer1_pop popup public_pop" id="layer1_popup">
    <div class="popup-box public_box layer1_box" id="layer1_box">
        <div class="popup-close"></div>
        <div class="color_div">
            <div class="div1 pup_color"></div>
            <div class="div2 pup_color"></div>
            <div class="div3 pup_color"></div>
            <div class="div4 pup_color"></div>
        </div>
        <div class="top_div layer1top" id="layer1top">
            <div class="recharge-text"><span>是否花费<b>3000</b>游戏币兑换<i></i>充值卡</span></div>
            <div class="recharge-input">
                <span>请输入手机号:<input type="text" id="telephone" maxlength="11"/></span>
            </div>
        </div>
        <div class="bottom_div layer1bottom" id="layer1bottom">
            <span class="cancel_btn left-span">取消兑换</span>
            <span class="confirm_btn right-span" id="recharge-confirm" onclick="exchange(<?php echo $_SESSION['uuid'] ?>)" attr-uuid = "<?php echo $_SESSION['uuid'] ?>">确认兑换</span>
        </div>
        
    </div>
</div>



<script>
    // 执行兑换操作
    var aa = $("#slideshow2").attr('attr_data');
    function exchange(uuid)
    {
         var id     = $("#recharge-confirm").attr('attr-id');
         var mobile = $("#telephone").val();
         if(!(/^1[34578]\d{9}$/.test(mobile))){ 
            alert("手机号码有误，请重填");
            return false; 
         }
         if (!uuid) {
            alert('请先登录');
            window.location.href = main_url;
        }
        
        // 1兑换失败-游戏币不足 2兑换成功 3兑换失败
        $.post("exchange/exchange", { id: id, uuid: uuid,mobile:mobile },function (data) {
            var o = JSON.parse(data);
            if (o.code === 1) {
                popupCreate.balance_fail(o.msg);
            } else if(o.code === 2){
                popupCreate.success("兑换成功");
            } else {
                popupCreate.fail(o.msg);
            }
        });
    }
    
    
    (function($){
        
        
    })(jQuery);
    
    
    
</script>

<?php $this->load->view('public/footer'); ?>