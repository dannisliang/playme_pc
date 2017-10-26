/**
 * Created by xuzhixiong on 2016/12/30.
 */
(function ($) {
    /*$.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    }*/
    var personal_center=function(){
        /*this.nowClick=$.getUrlParam('nowClick')||0;


        $($("#center_box2 li")[this.nowClick]).addClass("selected");
        $($("#center_right>div")[this.nowClick]).css("display","block");*/

        this.init=function(){
            $("#center_box2 i").each(function (index) {
                $(this).css("background", "url('../img/center" + (index + 1) + ".png') no-repeat center");
            })
            this.addClick();
        }

        this.addClick=function(){
            var that=this;

            $(".error>img").on("click",function(){
                var that1=this;
               that.checkMessage(that1);
            })

            $(".order_right span").bind("click",function(){
                var that2=this;
                that.checkRecord(that2);
            })
        }


        this.checkMessage=function(that1){
            $(that1).parents(".message_card").remove();
            if($(".message_card").length==0){
                $("#empty_message").removeClass("hide");
            }
        }

        this.checkRecord=function(that){
            $(that).parents(".center_card").remove();
            if($(".center_card").length==0){
                $("#order-menu").addClass("hide");
                $("#empty_record").removeClass("hide");
            }
        }
    }

    var personalCenter=new personal_center();
    personalCenter.init();

    var canbeLong=false;

    $(".message_text>p").each(function(){
        if($(this).text().length>96){
            $("i",this).css("display","inline");
            $("i",this).attr("canbeLong","true");
            $(this).siblings("div").show();
        }else{
            $(this).siblings("div").hide();
        }
    })
    $(".message_text>div>span").click(function(){
        if(!$(this).parents(".message_text").children("p").children("i").attr("canbeLong")){
            return;
        }
        if( $(this).parents(".message_text").children("p").css("overflow")=="hidden") {
            $(this).parents(".message_text").children("p").css("overflow", "visible");
            $(this).parents(".message_text").children("p").css("height", "auto");
            $(this).parents(".message_text").children("p").children("i").css("display","none");
            $("i",this).css("background", "url(../img/show.png)");
        }else{
            $(this).parents(".message_text").children("p").css("overflow", "hidden");
            $(this).parents(".message_text").children("p").css("height", "52px");
            $(this).parents(".message_text").children("p").children("i").css("display","inline");
            $("i",this).css("background", "url(../img/show1.png)");
        }
    })

    $(".message_card>span>img").hover(function(){
        $(this).attr("src","../img/error.png")
    },function(){
        $(this).attr("src","../img/errordown.png")
    })

    $(".exchange-page>div>i>img").hover(function(){
        $(this).attr("src","../img/error.png")
    },function(){
        $(this).attr("src","../img/errordown.png")
    })

    $(".exchange-page>div>i>img").bind("click",function(){
        $(this).parent().parent().remove();
        if($(".exchange-page>div").length==0){
            $("#empty_recharge").removeClass("hide");
            $("#recharge-menu").addClass("hide");
        }
    })
})(jQuery)

function statue(that,text){
    $(that).parents(".order_bottom").siblings(".order_number").children("b").text(text);
    $(that).parent().parent().addClass("hide");
}