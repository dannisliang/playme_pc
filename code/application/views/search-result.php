<?php $this->load->view('public/header'); ?>
<!------------todo------------>
<div class="public_jc category-pop search-pop" id="search-pop">
    <div class="tap-category">
        <div class="tap-in">
            <i>游戏分类</i>
            <a href="/game/playnum_list">人气</a>
            <a href="/game/online_list">网游</a>
            <a href="/game/common_list">小游戏</a>
        </div>
    </div>
    
    <div class="public_jcBox main-category search-box">
        <div class="exist-search">
            <h2>你好，找到<i></i>相关游戏<span><?php echo $data['total_count']; ?></span>个</h2>
        </div>
        <?php foreach ($data['list']  as $k=>$v):?>
        <span class="<?php if(($k+1)%2) { echo 'gameCard-left';} else {echo 'gameCard-right';} ?>">
            <a href="<?php echo '/game/detail?id='.$v['id']; ?>"><img class="icon" src="<?php echo $v['icon'] ?>"/></a>
            <div class="category-middle">
                <a href="<?php echo '/game/detail?id='.$v['id']; ?>"><?php echo $v['name'] ?></a>
                <p><img src="/img/fire1.png" class="fire">人气：<?php echo $v['popularity'] ?></p>
                <p><?php echo $v['info'] ?></p>
            </div>
            <span><a href="#" onclick="check_login('<?php echo $v['file_directory']?>','<?php echo $_SESSION['uuid']?$_SESSION['uuid']:0;  ?>')">玩一玩</a></span>
        </span>
        <?php endforeach;?>
    </div>

    <div class="category-menu">
        <?php echo $page_link?>
    </div>
</div>

<?php $this->load->view('public/footer'); ?>