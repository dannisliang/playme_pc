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
                    <li><a  class="selected" href="/pay/index"><span>游戏币充值</span><b>></b></a></li>
                    <li><a href="/pay/recharge_record"><span>充值记录</span><b>></b></a></li>
                </ul>
            </div>
        </div>

        <div class="recharge_box recharge_right">
            
            <!-- 充值操作 START -->
            <div class="detail_box recharge_page">
                <div class="detail detail_title">充值金额:</div>
                <div class="detail detail_top">
                    <span class="detail_first row1">10000</span>
                    <span class="row1">5000</span>
                    <span class="row1">2000</span>
                    <span class="last row1">1000</span>
                    <span class="span_selected detail_first">500</span>
                    <input type="text" id="ziDing" placeholder="自定义" maxlength="8"/>
                    <b><img src="/img/gantan.png" />1元人民币兑换100游戏币</b>
                </div>
                <div class="detail detail_bottom">
                    <div class="detail detail_title title_bottom">积分抵扣:</div>
                    <div class="calculate" id="calculate">
                        <span class="calculate_first" id="jian1"><img src="/img/jian.png"/></span>
                        <input class="integration" id="integration" value="0" maxlength="8"/>
                        <span id="jia1"><img src="/img/jia.png"/></span>
                    </div>
                    <div class="integral">
                    <span class="check">
                        <input type="checkbox" id="checkbox"/>
                        <label for="checkbox">全部使用</label>
                    </span>
                        <div class="box box1"><span><img src="/img/gantan.png"/>使用<i id="gold"></i>积分抵¥<i id="RMB"></i></span></div>
                        <div class="box box2"><span>积分余额：<i id="balance"><?php echo $points; ?></i></span></div>
                    </div>
                    <form action="/pay/pay_order" onsubmit="return toVaild()" method="post">
                        <input type="hidden" name="rmb" id="js_rmb" placeholder="" value=""/>
                        <input type="hidden" name="blcoin" id="js_blcoin" placeholder="" value=""/>
                        <input type="hidden" name="points" id="js_points" placeholder="" value=""/>
                        <div class="account">
                            <span>应支付: ¥<i id="integer"></i>.<i class="decimal" id="decimal"></i></span>
                            <button type="submit">去结算</button>
                        </div>
                    </form>
                </div>
            </div>
                
            <!-- 充值操作 END -->
        </div>

        <div class="public_jcBox recharge_success" style="display: none"></div>
    </div>
</div>
<script>
    
    // 表单赋值
    function toVaild()
    {
        var blcoin1  = $("#ziDing").val();
        var blcoin2 = $(".span_selected").text();
        if (!blcoin1) {
            var blcoin = blcoin2; 
        } else if(!blcoin2){
            var blcoin = blcoin1; 
        } else {
            var blcoin = 0; 
        }
        if (isNaN(blcoin)) {
            popupCreate.fail("请输入正确格式");
            return false;
        }
        
        if (blcoin == 0) {
            popupCreate.fail("充值金额不能为0");
            return false;
        }
        
        var points  = $("#integration").val();
        var rmb     = $("#integer").text();
        var rmb2    = $("#decimal").text();
        
        $("#js_rmb").val(rmb+"."+rmb2);
        $("#js_points").val(points);
        $("#js_blcoin").val(blcoin);
        return true;
    }
    

</script>
<?php $this->load->view('public/footer'); ?>