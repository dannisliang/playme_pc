<?php $this->load->view('public/header'); ?>
<!------------todo------------>
<div class="popup" id="play_popup">
    <div class="popup-box game_box" id="game_box">
        <div class="popup-close"></div>
        <div class="rotate">旋转屏幕</div>
        <iframe frameborder="no" border="0" src="<?php echo $data['file_directory']?>">

        </iframe>
    </div>
</div>

<div class="public_jc detail-pop" id="detail-pop">
    <div class="public_jcBox gameDetail-box">
        <div class="gameDetail-left">
            <div class="left-box gameDetail-top">
                <i><img src="<?php echo $data['icon']?>"/></i>
                <div class="detail-text">
                    <div class="jieshao">
                        <h1><?php echo $data['name']?></h1>
                        <span>人气：<?php echo $data['play_num']?></span>
                        <p>介绍：<?php echo $data['info']?></p>
                        <p>玩法：<?php echo $data['operation_info']?></p>
                    </div>

                    <div class="kaishi">
<!--                        popup-id="play_popup"-->
                        <?php if($data['buy_status'] == 1 || $data['blcoin_curr'] == 0){?>
                            <?php if($data['game_type'] == 3): ?>
                            <button href="" class="popup-open" style="color: rgb(255, 255, 255); background-color: rgb(235, 69, 79);" onclick="download_('<?php echo $data['file_directory']; ?>')">下载</button>
                            <?php else: ?>
                            <button class="popup-open" style="color: rgb(255, 255, 255); background-color: rgb(235, 69, 79);" onclick="check_login('<?php echo $_SESSION['uuid']?$_SESSION['uuid']:0;  ?>')">开始游戏</button>
                            <?php endif;?>
                        <?php } else {?>
                            <button class="popup-open" onclick="check_login_2('<?php echo $data['blcoin_curr']?>')"><b></b><i><?php echo $data['blcoin_curr']?></i></button>
                        <?php }?>
                        <span><img src="../img/<?php echo $data['collect_status']?>.png" id="collect" onclick="collect_game('<?php echo $data['id']?>')"/><div>收藏</div></span>
                    </div>
                </div>
            </div>

            <div class="left-box gameDetail-bottom">
                <div class="fanye detail-left" id="detail-left"></div>
                <div class="gameDetail-show" id="gameDetail-show">
                    <div class="detail-slide" id="detail-slide">
                        <?php foreach ($data['imgs'] as $k => $v){?>
                            <div class="detail-page" <?php if($k == 0 ){?>style="display: block"<?php }?>><img src="<?php echo $v ?>"/></div>
                        <?php     }?>
                    </div>
                </div>
                <div class="fanye detail-right" id="detail-right"></div>
            </div>
        </div>

        <div class="gameDetail-right">
            <div class="tuijian-title">推荐游戏</div>
            <div class="tuijian-game">
                <?php foreach ($recommend_data['list'] as $k => $v){?>   
                    <?php if($k < 2){ ?>
                        <span class="tuijian-<?php echo $v['style']?> tuijian-first">
                   <?php } else {?>
                        <span class="tuijian-<?php echo $v['style']?>">
                   <?php }?>
                        <a href="detail?id=<?php echo $v['id']?>">
                            <img src="<?php echo $v['icon']?>"/>
                            <div><?php echo $v['name']?></div>
                        </a>
                    </span>
                <?php     }?> 
            </div>
        </div>
    </div>
</div>

<!-----是否购买----->
<div class="public_pop popup buy" id="buy">
    <div class="popup-box public_box buy_box" id="buy_box">
        <div class="popup-close"></div>
        <div class="color_div">
            <div class="div1 pup_color"></div>
            <div class="div2 pup_color"></div>
            <div class="div3 pup_color"></div>
            <div class="div4 pup_color"></div>
        </div>
        <div class="buy-title"><span>余额：<i>200</i><b></b></span></div>
        <div class="buy-text">是否使用<i><?php echo $data['blcoin_curr']?></i>游戏币购买游戏</div>
        <div class="bottom_div buy-bottom">
            <span>取消购买</span>
            <span class="right-span" id="" onclick="buy_game('<?php echo $data['id']?>')">确认购买</span>
        </div>
    </div>
</div>
<script>
    // 下载游戏
    function download_(url)
    {
        location.href   = url;
    }
    function check_login_2(src){
        $.ajax({
            url : "buy_game_page",
            type : "get",
            data : {src},
            dataType : "text",
            cache : false,
            success: function (data) {
                callback_param = data;
                if(callback_param != 'false'){
                   popupCreate.paymentornot(callback_param , src);
                }
                else{
                    popupCreate.fail("请登录");
                }
            }
        });
    }
    function buy_game(game_id){
        $.ajax({
            url : "buy_game",
            type : "get",
            data : {game_id},
            dataType : "text",
            cache : false,
            success: function (data) {
                callback_param = data;
                if(callback_param != 'success'){
                    popupCreate.fail(callback_param);
                }
                else{
                    window.location.reload();
                }
            }
        });
    }
    function collect_game(game_id){
        $.ajax({
            url : "collect_game",
            type : "get",
            data : {game_id},
            dataType : "text",
            cache : false,
            success: function (data) {
                callback_param = data;
                callback_param_arr = callback_param.split('|');
                if(callback_param_arr[0] == 'success'){
                    $("#collect").attr("src","../img/"+callback_param_arr[1]+".png")
                }
                else{
                    popupCreate.fail(callback_param_arr[1]);
                }
            }
        });
    }
</script>
<?php $this->load->view('public/footer'); ?>