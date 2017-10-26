<?php $this->load->view('public/header'); ?>
<!------------todo------------>
<div class="public_jc recharge center" id="center">
    <div class="whole_recharge">
        <div class="recharge_box recharge_left center_left">
            <div class="box1 center_box1">
                <img class="head" src="<?php if($u_info['image'] && substr($u_info['image'],7) == 'http://') { echo $u_info['image'];} else{ echo '/img/temp/userpicbig.png';} ?>"/>
                <div><?php echo $u_info['name'] ?></div>
                <br/>
                <div>
                    <img src="/img/blb-icon.png">
                    <span>游戏币:&nbsp;<i id="sum"><?php echo $u_info['blcoin']?></i></span>
                    <a target="_blank" href="/pay/index">充值</a>
                </div>
            </div>

            <div class="center_box2" id="center_box2">
                <ul>
                    <li><a href="/user/user_center"><span><i></i>我的收藏</span><b>></b></a></li>
                    <li><a href="/user/news"><span><i></i>我的消息</span><b>></b></a></li>
                    <li  class="selected"><a href="/user/order"><span><i></i>充值订单</span><b>></b></a></li>
                    <li><a href="/user/record"><span><i></i>兑换记录</span><b>></b></a></li>
                    <li><a href="/user/question"><span><i></i>常见问题</span><b>></b></a></li>
                    <li><a href="<?php echo base_url() ?>"><span><i></i>返回</span></a></li>
                </ul>
            </div>
        </div>
        
        <div class="recharge_box recharge_right center_right" id="center_right">
            <div class="detail_page record_page">
                <div class="order-area">
                <?php if($list): ?>
                <?php foreach ($list as $k=>$v): ?>
                <div class="public_card center_card">
                    <div class="card_in">
                        <div class="order_number">
                            <span>订单号:&nbsp;<?php echo $v['order_no'] ?></span>&nbsp;
                            <b>
                                <?php if($v['status'] == 0): ?>交易失败
                                <?php elseif($v['status'] == 1): ?>交易成功
                                <?php elseif($v['status'] == 2): ?>待支付
                                <?php elseif($v['status'] == 3): ?>已关闭
                                <?php elseif($v['status'] == 4): ?>等待支付结果
                                <?php endif; ?>
                            </b>
                        </div>
                        <div class="order_bottom">
                            <div class="order_left">
                                <div class="jifen"><span>+&nbsp;<?php echo $v['blcoin']; ?></span><i></i></div>
                                <div class="time"><?php echo $v['create_time']; ?></div>
                            </div>
                            <div class="order_right">
                                <div class="order_right_1 order_right_1_2 <?php if ($v['status'] != 2){ echo 'hide';} ?>">
                                    <div class="pay"><a target="_blank" href="/pay/pay_again?id=<?php echo $v['id']?>">支付</a></div>
                                    <div class="cancel"><a onclick="cancel_order(this,<?php echo $v['id']?>);return false;">取消</a></div>
                                </div>
                                <div class="order_right_1 order_right_1_1"><span onclick="delete_order(<?php echo $v['id']?>);return false;"></span></div>
                            </div>
                            <div class="order_middle">
                                <div>支付金额：<span><b>¥</b>&nbsp;<?php echo round($v['pay_rmb']/100,2); ?><b></b></span></div>
                                <div>积分抵扣：<span><?php echo $v['points']; ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif;?>
                
                <div class="detail_page empty_box <?php if($list) echo 'hide';?>" id="empty_record" >
                    <img src="../img/empty.png"/>
                    <div>没有订单记录</div>
                </div>
                <div class="category-menu center_menu <?php if(!$list) echo 'hide';?>">
                    <?php echo $page_link ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // 取消订单支付状态
    function cancel_order(that,id) {
        statue(that,'已关闭');
        $.post("/pay/cancel_order", { id: id},function (data) {
            var o = JSON.parse(data);
            if (o.code === 1) {
                popupCreate.success("取消成功");
            } else {
                popupCreate.fail(o.msg);
            }
        });
    }
    
    // 删除订单
    function delete_order(id) {
        $.post("/pay/delete_order", { id: id},function (data) {
            var o = JSON.parse(data);
            console.log(data);
            if (o.code === 1) {
                popupCreate.success("删除成功");
            } else {
                popupCreate.fail(o.msg);
            }
        });
        
        window.location.href = main_url+"user/order";
    }
</script>
<?php $this->load->view('public/footer'); ?>