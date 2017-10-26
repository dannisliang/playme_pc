<?php $this->load->view('public/header'); ?>
<!------------todo------------>
<div class="tap-category">
    <div class="tap-in">
        <i>游戏分类</i>
        <a href="/game/playnum_list">人气</a>
        <a href="/game/online_list">网游</a>
        <a href="/game/common_list">小游戏</a>
    </div>
</div>
<div class="public_jc unfound-pop" id="unfound">
    <div class="public_jcBox unfound-box">
        <div class="unfound-center">
            <div class="unfound-top">
                <img src="/img/monster.png"/>
                <span>
                    <h1>很抱歉，没有找到你查找的游戏</h1>
                    <p>提示：你可以减少搜索关键字，来找到你要查找的小游戏</p>
                    <p>例如：你搜索“<i>大嘴怪跳跳跳</i>”，可改为搜索“<i>跳</i>”，这样可以搜到更多的游戏哦。</p>
                </span>
            </div>
            <div class="unfound-bottom">
                <p>我们的编辑已经知道您没有找到你想玩的游戏，我们会尽快把游戏补上！</p>
                <p>谢谢你，欢迎经常来i百联玩游戏!</p>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('public/footer'); ?>