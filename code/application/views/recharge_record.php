<?php $this->load->view('public/header'); ?>
<!------------todo------------>
<div class="public_jc recharge" id="recharge">
    <div class="whole_recharge">
        <div class="recharge_box recharge_left">
            <div class="box1">
                <img class="head" src="<?php if($u_info['image'] && substr($u_info['image'],7) == 'http://') { echo $u_info['image'];} else{ echo '/img/temp/userpicbig.png';} ?>"/>
                <div><?php echo $u_info['name'] ?></div>
                <br/>
                <div><img src="/img/blb-icon.png" class="blb-icon"><span>游戏币:&nbsp;<i id="sum"><?php echo $u_info['blcoin']?></i></span></div>
            </div>

            <div class="box2">
                <ul>
                    <li><a href="/pay/index"><span>游戏币充值</span><b>></b></a></li>
                    <li><a href="/pay/recharge_record" class="selected"><span>充值记录</span><b>></b></a></li>
                </ul>
            </div>
        </div>

        <div class="recharge_box recharge_right">
            <div class="detail_box record_page">
                <?php if($list): ?>
                    <?php foreach ($list as $k=>$v):?>
                    <div class="card">
                        <div class="card_child card_left">
                            <div class="card_title">充值</div>
                            <div class="jinE">支付金额：<b>¥</b><i><?php echo ($v['rmb']/100); ?></i></div>
                            <div class="jinE">积分抵扣: <i><?php echo $v['points']; ?></i></div>
                        </div>
                        <div class="card_child card_right">
                            <span class="red">+&nbsp;<?php echo $v['blcoin']; ?></span>
                            <span class="time_record"><?php echo $v['create_time']; ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <div class="detail_page empty_box" id="empty_message">
                    <img src="../img/empty.png"/>
                    <div>没有充值记录</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('public/footer'); ?>