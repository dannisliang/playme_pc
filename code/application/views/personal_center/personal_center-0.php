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
                    <li class="selected"><a href="/user/user_center"><span><i></i>我的收藏</span><b>></b></a></li>
                    <li><a href="/user/news"><span><i></i>我的消息</span><b>></b></a></li>
                    <li><a href="/user/order"><span><i></i>充值订单</span><b>></b></a></li>
                    <li><a href="/user/record"><span><i></i>兑换记录</span><b>></b></a></li>
                    <li><a href="/user/question"><span><i></i>常见问题</span><b>></b></a></li>
                    <li><a href="<?php echo base_url() ?>"><span><i></i>返回</span></a></li>
                </ul>
            </div>
        </div>
        
        <div class="recharge_box recharge_right center_right" id="center_right">
            <div class="detail_page collection_page">
                <div class="slide_area" >
                    <?php if($list): ?>
                    <?php foreach ($list as $k=>$v): ?>
                    <span class="<?php if($k%5 == 0){ echo "first";} elseif(($k+1)%5 == 0) { echo 'last'; } ?>"><a href="/game/detail?id=<?php echo $v['id']?>"><img src="<?php echo $v['icon'] ?>"/><div><?php echo $v['name'] ?></div></a></span>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="category-menu center_menu <?php if(!$list) echo 'hide';?>">
                    <?php echo $page_link; ?>
                </div>
                <div class="detail_page empty_box <?php if($list) echo 'hide';?>">
                    <img src="../img/empty.png"/>
                    <div>没有收藏记录</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('public/footer'); ?>