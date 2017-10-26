/**
 * Created by xuzhixiong on 2016/12/19.
 */
(function ($) {
    // main_url    = "http://www.blpc.com/";
    main_url    = "http://uat.apis.ibl.cn/";
    $("#cancel").on("click", function () {
        $("#login").removeClass("hide");
        $("#login_success").addClass("hide");
    })

    $(".popup-open").on("click", function () {
        var json = $(this).attr("exchange-data");
        try {
            if (json) {
                json = JSON.parse(json);
                $("#exchange_popup").find(".layer0top>img").attr("src", json.img);
                $("#exchange_popup").find(".title1").html(json.title);
                $("#exchange_popup").find(".p1>span").html(json.num);
                $("#exchange_popup").find(".desc").html(json.desc);
                $("#exchange_popup").find(".float_div>span>span").html(json.coin);
                $("#exchange_popup").find(".float_div>span>span").html(json.coin);
                $("#exchange_popup").find(".layer0bottom>i>b").html(json.mycoin);
                $("#exchange_popup").find(".popup-open").attr("data", JSON.stringify(json));
            }
        } catch (e) {
            console.log("数据错误");
        }
    });

    $("#layer0bottom .popup-open").on("click", function () {
        var json = $(this).attr("data");
        try {
            if (json) {
                json = JSON.parse(json);
                $(".recharge-text b").html(json.coin);
                $(".recharge-text i").html(json.title);
                $("#layer1bottom #recharge-confirm").attr("attr-id", json.id);
            }
        } catch (e) {
            console.log("数据错误");
        }
    });

    $("#first_header_search_input").bind("input focus propertychange", function () {
        $(".header-input-show").css("display", "block");
    })

    $(document).on("click", function (e) {
        var e = e || window.event;
        var elem = e.target || e.srcElement;
        while (elem) { //循环判断至跟节点，防止点击的是div子元素
            if (elem.className && elem.className == "header-search") {
                return;
            }

            elem = elem.parentNode;
        }
        $(".header-input-show").css("display", "none");
    })

  /*  $(document).ready(function () {
        $(".slide-list>div>div").each(function(element){
            if(!$(element).hasClass("hide")){
                $(".slide-list>div").attr("popup-id", "");
            }
        })
    })*/
})(jQuery);