<?php $this->load->view('public/header'); ?>
<!------------todo------------>
<div class="title-image-category">
    <div></div>
</div>
<div class="public_jc category-pop" id="category-pop">
    <div class="tap-category">
        <div class="tap-in">
            <i>游戏分类</i>
            <a href="/game/playnum_list">人气</a>
            <a class="category-active" href="/game/online_list">网游</a>
            <a href="/game/common_list">小游戏</a>
            <a href="/game/download_list">下载</a>
        </div>
    </div>

    <div class="public_jcBox main-category">
        <?php foreach ($list['list'] as $k => $v){?>  
        <span class="gameCard-<?php echo $v['style']?>">
            <a href="detail?id=<?php echo $v['id']?>"><img class="icon" src="<?php echo $v['icon']?>"/></a>
                <div class="category-middle">
                    <a href="detail?id=<?php echo $v['id']?>"><?php echo $v['name']?></a>
                    <p><img src="../img/fire1.png" class="fire">人气：<?php echo $v['play_num']?></p>
                    <p><?php echo $v['info']?></p>......
                </div>
            <span><a href="detail?id=<?php echo $v['id']?>">玩一玩</a></span>
        </span>
        <?php     }?>
    </div>

    <div class="category-menu" id="category-menu">
        <?php echo $page_link?>
    </div>
</div>
<?php $this->load->view('public/footer'); ?>