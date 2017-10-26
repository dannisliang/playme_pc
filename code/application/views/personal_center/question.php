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
                    <li><a href="/user/order"><span><i></i>充值订单</span><b>></b></a></li>
                    <li><a href="/user/record"><span><i></i>兑换记录</span><b>></b></a></li>
                    <li   class="selected"><a href="/user/question"><span><i></i>常见问题</span><b>></b></a></li>
                    <li><a href="<?php echo base_url() ?>"><span><i></i>返回</span></a></li>
                </ul>
            </div>
        </div>

        <div class="recharge_box recharge_right center_right" id="center_right">
            <div class="detail_page problem_page">
                <ul>

                    <li>
                        <h1>1.游戏币有什么用?</h1>
                        游戏币是游戏中心的通用货币，可以对部分游戏进行游戏内充值；同时也可以在“兑换”页面兑换相应商品。
                    </li>

                    <li>
                        <h1>2.怎么获得游戏币?</h1>
                        游戏币可以通过人民币充值和百联积分抵扣两种方式获得；同时通过不定期的活动和任务也可以获得；1人民币=100游戏币；1百联积分=1游戏币。
                    </li>

                    <li>
                        <h1>3.百联积分有什么用?</h1>
                        百联积分可在充值游戏币时按照1百联积分：1游戏币的比例进行抵扣。
                    </li>

                    <li>
                        <h1>4.怎么查看游戏币?</h1>
                        你可以在“游戏账户”页面。查看你当前的游戏币和百联积分。
                    </li>

                    <li>
                        <h1>5.游戏需要下载才能玩吗?</h1>
                        游戏中心内所有游戏的无需下载，点击打开即可进行游戏
                    </li>

                    <li>
                        <h1>6.收藏游戏有什么作用?</h1>
                        收藏游戏后，你可以在“账户-收藏”，页面直接进入游戏，便于查找。
                    </li>

                    <li>
                        <h1>7.有的游戏无法正常加载怎么办?</h1>
                        由于适配的关系，所以有些游戏可能不能完美的全屏呈现，你可能需要双击来让它适应屏幕环境
                    </li>

                    <li>
                        <h1>8.如何联系网页游戏客服?</h1>
                        网页游戏客服咨询QQ：379192836
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('public/footer'); ?>