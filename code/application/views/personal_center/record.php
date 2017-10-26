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
                    <span>游戏币:&nbsp;<i id="sum">255</i></span>
                    <a target="_blank" href="/pay/index">充值</a>
                </div>
            </div>

            <div class="center_box2" id="center_box2">
                <ul>
                    <li><a href="/user/user_center"><span><i></i>我的收藏</span><b>></b></a></li>
                    <li><a href="/user/news"><span><i></i>我的消息</span><b>></b></a></li>
                    <li><a href="/user/order"><span><i></i>充值订单</span><b>></b></a></li>
                    <li class="selected"><a href="/user/record"><span><i></i>兑换记录</span><b>></b></a></li>
                    <li><a href="/user/question"><span><i></i>常见问题</span><b>></b></a></li>
                    <li><a href="<?php echo base_url() ?>"><span><i></i>返回</span></a></li>
                </ul>
            </div>
        </div>

        <div class="recharge_box recharge_right center_right" id="center_right">
            <div class="detail_page exchange-page">
                <?php foreach ($list['list'] as $k =>$v){?>
                    <div>
                        <img src="<?php echo $v['icon']?>"/>
                       <div>
                           <h1><?php echo $v['name']?></h1>
                           <p>已兑换：<i>1</i>份</p>
                           <p>时间：<?php echo $v['time']?></p>
                           <div><i></i><span><?php echo $v['blcion']?></span></div>
                       </div>
                       <i><img src="../img/errordown.png" onclick="delete_exchange_his(<?php echo $v['id'] ?>)"/></i>
                    </div>
                <?php }?>
            </div>

            <div class="detail_page empty_box <?php if($list['list']) echo 'hide';?>" id="empty_record" >
                    <img src="../img/empty.png"/>
                    <div>没有兑换记录</div>
            </div>
            <?php if($list['num'] > 1){?>
                <div class="category-menu center_menu"  id="recharge-menu">
                    <?php echo $page_link ?>
                </div>
            <?php }?>
            
        </div>
    </div>
</div>
<script>
    // 删除消息
    function delete_exchange_his(id) {
        $.post("delete_exchange_his", { id: id},function (data) {
            var o = JSON.parse(data);
        });
        window.location.href = main_url+"user/record";
    }
</script>
<?php $this->load->view('public/footer'); ?>