/**
 * Created by xuzhixiong on 2017/1/4.
 */
(function($){
    $("#tap-head span").bind("click",function(){
        var index=$(this).index();
        $("#tap-head span").eq(index).addClass("tap-active").siblings("span").removeClass("tap-active");
        $(".main-text .protect-page").eq(index).show().siblings(".protect-page").hide(0);
    })
})(jQuery)