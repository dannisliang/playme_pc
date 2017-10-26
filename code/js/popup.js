/**
 * Created by chenxiangye on 2016/12/15.
 */
(function ($) {
    $.popupList = {};
    $.fn.getPopup = function (id) {
        return $.popupList[id];
    };
    $.fn.popup = function (cfg) {
        var Popup = function (elm, cfg) {
            this.show = function () {
                $("body").attr("onmousewheel", "return false")
                elm.height($("body").height());

                elm.animate({
                    display: "show",
                    opacity: 1
                }, 300);
                elm.find(".popup-box").animate({
                    opacity: 1,
                    top: "200px"
                }, 300);
            };
            this.hide = function () {
                $("body").attr("onmousewheel", "return true");
                elm.animate({
                    opacity: 0
                }, 300, function () {
                    $(this).hide();
                });
                elm.find(".popup-box").animate({
                    opacity: 1,
                    top: 0
                }, 300);
            };

            var that = this;

            elm.find(".popup-close").on("click", function () {
                that.hide();
            });


            $(".bottom_div span").on("click", function () {
                if($(this)[0].id=="recharge-confirm"){
                    return;
                };
                that.hide();
            });

            $(".alipay-pop>div>div:last-child>span").on("click",function(){
                that.hide();
            })
        };
        return this.each(function () {
            $.popupList[$(this).attr("id")] = new Popup($(this), cfg);
        });
    }

    $(".popup").popup({});
    $(".popup-open").on("click", function () {
        if($(this).attr("popup-id")!=""){
            $.fn.getPopup([$(this).attr("popup-id")]).show();
        }
    });
})(jQuery);
var popupCreate = {
    closeOther: function () {
        for (var elem in $.popupList) {
            $.fn.getPopup("" + elem).hide();
        }
    },

    success: function (text) {//兑换/充值/购买成功
        this.closeOther();
        setTimeout(function(){$("#exchanged_box_txt").text(text)},300);
        $.fn.getPopup("exchanged_popup").show();
    },

    fail: function (text) {//失败
        this.closeOther();
        setTimeout(function(){$("#fail_txt").text(text)},300);
        $.fn.getPopup("layer2_popup").show();
    },

    balance_fail: function (sum) {//失败,上面显示游戏币余额的
        this.closeOther();
        setTimeout(function(){ $("#buy-sum").text(sum)},300);
        $.fn.getPopup("buy-fail").show();
    },

    paymentornot:function(sum,pay){//sum是游戏币余额，pay是花费多少游戏币购买
        this.closeOther();
        setTimeout(function(){
            $(".buy-title>span>i").text(sum);
            $(".buy-text>i").text(pay)
        },300);
        $.fn.getPopup("buy").show();
    },

    playpopup:function(){//玩游戏的弹窗
        this.closeOther();
        $.fn.getPopup("play_popup").show();
    }
}