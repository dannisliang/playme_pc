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
                    <li class="selected"><a href="/user/news"><span><i></i>我的消息</span><b>></b></a></li>
                    <li><a href="/user/order"><span><i></i>我的订单</span><b>></b></a></li>
                    <li><a href="/user/record"><span><i></i>兑换记录</span><b>></b></a></li>
                    <li><a href="/user/question"><span><i></i>常见问题</span><b>></b></a></li>
                    <li><a href="<?php echo base_url() ?>"><span><i></i>返回</span></a></li>
                </ul>
            </div>
        </div>

        <div class="recharge_box recharge_right center_right" id="center_right">
            <div class="detail_page message_page">
                <?php if ($list): ?>
                <?php foreach ($list as $k=>$v): ?>
                <div class="message_card">
                    <img src="../img/horn.png" class="horn"/>
                    <div class="message_text">
                        <p><?php echo $v['content'] ?><i>......</i></p>
                        <span><?php echo $v['sender_time'] ?></span>
                        <div><span><i></i>展开全文</span></div>
                    </div>
                    <span class="error" ><img src="../img/errordown.png" onclick="delete_news(<?php echo $v['id'] ?>)" /></span>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="detail_page empty_box" id="empty_message">
                    <img src="../img/empty.png"/>
                    <div>没有消息记录</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
    // 删除消息
    function delete_news(id) {
        $.post("delete_news", { id: id},function (data) {
            var o = JSON.parse(data);
//            if (o.code === 1) {
//                popupCreate.success("删除成功");
//            } else {
//                popupCreate.fail(o.msg);
//            }
        });
    }
</script>
<?php $this->load->view('public/footer'); ?>