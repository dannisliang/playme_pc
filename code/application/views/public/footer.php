
<!--登录弹窗-->
<!--<div class="login_pop popup" id="login_popup">
    <div class="popup-box login_box" id="login_box">
        <div class="popup-close"></div>
        <h2>用户登录</h2>
        <input type="text" placeholder="手机邮箱"/>
        <input type="password" placeholder="密码"/>
        <input type="checkbox" class="checkbox" id="check"/><label for="check">记住密码</label>
        <a class="forget" href="password.html" target="_self">忘记密码?</a>
        <button>登录</button>
    </div>
</div>-->

<!--注册-->



<!--------成功------->
<div class="public_pop popup buy" id="exchanged_popup">
    <div class="popup-box public_box buy_box">
        <div class="popup-close"></div>
        <div class="color_div">
            <div class="div1 pup_color"></div>
            <div class="div2 pup_color"></div>
            <div class="div3 pup_color"></div>
            <div class="div4 pup_color"></div>
        </div>
        <div class="success-text">
            <img src="/img/finish.png"/>
            <div id="exchanged_box_txt">兑换成功！</div>
        </div>
    </div>
</div>

<!--------失败------->
<div class="public_pop popup buy" id="layer2_popup">
    <div class="popup-box public_box buy_box">
        <div class="popup-close"></div>
        <div class="color_div">
            <div class="div1 pup_color"></div>
            <div class="div2 pup_color"></div>
            <div class="div3 pup_color"></div>
            <div class="div4 pup_color"></div>
        </div>
        <div class="success-text">
            <img src="/img/error&fail.png"/>
            <div id="fail_txt">兑换失败！</div>
        </div>
    </div>
</div>



<!--------失败带余额------->
<div class="layer2_pop popup public_pop" id="buy-fail">
    <div class="popup-box public_box layer2_box" id="buyFail-box">
        <div class="popup-close"></div>
        <div class="color_div">
            <div class="div1 pup_color"></div>
            <div class="div2 pup_color"></div>
            <div class="div3 pup_color"></div>
            <div class="div4 pup_color"></div>
        </div>
        <div class="buy-title"><span>余额：<i id="buy-sum">5</i><b></b></span></div>
        <div class="top_div layer2top layer_public buy-middle">
            <span>游戏币不足，请充值!</span>
        </div>
        <div class="bottom_div layer2bottom">
            <span class="cancel_btn left-span">取消</span>
            <span class="go_buy right-span"><a target="_blank" href="recharge.html">前往充值</a></span>
        </div>
    </div>
</div>
<!------------end------------>


<!-- fooderV4.jsp -->
<div id="footer" class="footer">
    <div class="bottom-bl">
        <div class="redLine"></div>
        <p>
            <a href="/user/user_center">用户中心</a>
            <span>|</span>
            <a target="_blank" href="/other/protocal">服务协议</a>
            <span>|</span>
            <a target="_blank" href="/other/rights">用户权益</a>
            <span>|</span>
            <a target="_blank" href="/other/addiction">防沉迷</a>
            <span>|</span>
            <a target="_blank" href="/other/dispute">交易纠纷</a>
            <span>|</span>
            <a target="_blank" href="/other/noadult_protect">家长监护</a>
            本公司游戏仅适合18周岁以上玩家
        </p>
        <p class="copyright">抵制不良游戏，拒绝盗版游戏，注意自我保护，谨防受骗上当，适度游戏益脑，沉迷游戏伤身，合理安排时间，享受健康生活。
        </p>
        <div class="copy">
            百联集团有限公司旗下 百联全渠道电子商务有限公司版权所有<span>|</span>客服电话：400-900-8800<span>|</span> 沪ICP备15028847号
        </div>
        <a target="_blank" href="/img/huwangwen.png">
            网络文化经营许可证编号：沪网文[2016] 5524-402 号
        </a>
        <div class="getpolice">
            <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=31010102002366&bl_mmc=baidu_-_sem_-_582_-_0" target="_blank">
                <img src="http://res11.iblimg.com/respc-1/resources/v4.0/css/i/gongan.png">
                <span style="">沪公网安备  31010102002366号</span>
            </a>
            <a href="http://shwg.dianping.com?bl_mmc=baidu_-_sem_-_582_-_0" target="_blank">
                <img src="http://res11.iblimg.com/respc-1/resources/v4.1/widget/footer1200/i/wangjing.png">
                <span style="">网购大家评</span>
            </a>
            <a href="http://218.242.124.22:8081/businessCheck/verifKey.do?showType=extShow&serial=9031000020160729145737000001099658-SAIC_SHOW_310000-20160406145114141192&signData=MEYCIQCUhx+ulQL4t9jJgfgCH1oeTxexuWOVww3Hel3/UlRyLQIhAO2fVtZDl0EkYb0p/3M50cXqe694wDQoJNuUKNdQNOWf&bl_mmc=baidu_-_sem_-_582_-_0"
               target="_blank">
                <img src="http://res11.iblimg.com/respc-1/resources/v4.1/widget/footer1200/i/gongs.png">
                <span style="">上海工商</span>
            </a>
        </div>
    </div>
</div>
<div id="err_window_msg" msg-data = '{"show_message":"<?php echo $_GET['show_message']?>","window_type":"<?php echo $_GET['window_type']?>"}'></div>
<script type="text/javascript" src="http://res11.iblimg.com/respc-1/resources/v4.2/unit/jquery-1.8.2.min.js?version="></script>
<script type="text/javascript" src="http://res11.iblimg.com/respc-1/resources/v4.2/unit/bl.js"></script>
<script type="text/javascript" src="http://res11.iblimg.com/respc-1/resources/v4.0/js/product/cookie.js?version="></script>
<script type="text/javascript" src="http://res11.iblimg.com/respc-1/resources/v4.0/js/commons/tools.js?version="></script>
<script type="text/javascript" src="http://res11.iblimg.com/respc-1/resources/v4.2/js/commons/navshow.js"></script>
<!--banner-->
<script type="text/javascript" src="/js/slideshow.js?a=1"></script>
<script type="text/javascript" src="/js/convert.js?a=1"></script>
<script type="text/javascript" src="/js/popup.js?a=1"></script>
<script type="text/javascript" src="/js/index.js"></script>
<script type="text/javascript" src="/js/non-adult.js"></script>
<script type="text/javascript" src="/js/ie/selectivizr-min.js"></script>
<script type="text/javascript" src="/js/personal_center.js"></script>
<script type="text/javascript" src="/js/detail.js"></script>
<script type="text/javascript" src="/js/recharge.js"></script>
<!--[if lte  IE 9]>
<script type="text/javascript" src="js/ie/selectivizr-min.js"></script>
<![endif]-->
</body>
</html>
<script>
    $(function () {
        // 后端错误弹框提示
        var json = $("#err_window_msg").attr("msg-data");
        json = JSON.parse(json);
        
        
        if (json.window_type) {// 1普通错误弹窗 2含有"前往充值"弹窗 
             popupCreate.fail(json.show_message);
             // var url    = window.location.href;
             // url = url.substring(0,url.indexOf("?"));
             // history.pushState("", "Title", url);
             return;
        }
        
    });
    
    /**
     * 校验用户是否登录
     * @returns {undefined}
     */
    function check_login(uuid)
    {
        if(uuid != 0){
            popupCreate.playpopup();
            return;
         } else{
             popupCreate.fail("请登录");
             return;
         }
    }
    
</script>