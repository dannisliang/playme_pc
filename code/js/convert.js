/**
 * Created by chenxiangye on 2016/12/14.
 */
(function($){

    $.fn.slideSelf = function(cfg){
        var SlideSelf = function(elm, cfg){
            var defaults = {
                "dots" : false,
                "pageNow":0
            };
            this.config = $.extend(defaults, cfg);
            var w = elm.width();
            var list = elm.find(".slide-list");
            var left_btn = elm.find(".left-btn");
            var right_btn = elm.find(".right-btn");
            var dot = elm.find(".dot");
            var slide_scroll = elm.find(".slide-scroll");
            slide_scroll.width(list.length*w);
            //slide_scroll.css({"transition":"transition 2s"});

            this.init = function(){
                for(var i = 0 ;i<list.length ;i++){
                    var li = list[i];
                    $(li).css("left",w*i);
                    this.setDot(i);
                }
                this.addClick();
            };
            this.setDot = function(no){
                var that = this;
                var i = $("<i class='i_"+no+"' no='"+no+"'/>");
                if(no==0){
                    i.addClass("active");
                }
                i.html(no+1).appendTo(dot).on("click",function(){
                    that.goto($(this).attr("no"));
                });
            };

            this.changeDot = function(){
                dot.find("i").removeClass("active");
                dot.find(".i_"+this.config.pageNow).addClass("active");
            };

            this.addClick = function(){
                var that = this;
                left_btn.click(function(){
                    var that1=this;
                    that.up(that1);
                });
                right_btn.click(function(){
                    var that2=this;
                    that.next(that2);
                });

                left_btn.hover(function(){
                    that.config.pageNow==0?$(this).css({"cursor":"text","background-image":"url('img/left.png')"}):
                        $(this).css({"cursor":"pointer","background-image":"url('img/left1.png')"})
                },function(){
                    $(this).css("background-image","url('img/left.png')");
                })

                right_btn.hover(function(){
                    that.config.pageNow==list.length-1?$(this).css({"cursor":"text","background-image":"url('img/right.png')"}):
                        $(this).css({"cursor":"pointer","background-image":"url('img/right1.png')"})
                },function(){
                    $(this).css("background-image","url('img/right.png')");
                })

            };

            this.goto = function(i){
                this.config.pageNow = i;
                slide_scroll.css("left",-w*this.config.pageNow);
                this.changeDot();
            };
            this.next = function(that){
                this.config.pageNow++;
                if(this.config.pageNow<list.length){
                    slide_scroll.css("left",-w*this.config.pageNow);
                    if(this.config.pageNow==list.length-1){
                        $(that).css({"cursor":"text","background-image":"url('img/right.png')"})
                    }
                }else{
                    this.config.pageNow = list.length-1;
                }
                this.changeDot();
            };
            this.up = function(that){
                this.config.pageNow--;
                if(this.config.pageNow>=0){
                    slide_scroll.css("left",-w*this.config.pageNow);
                    if(this.config.pageNow==0){
                        $(that).css({"cursor":"text","background-image":"url('img/left.png')"})
                    }
                }else{
                    this.config.pageNow = 0;
                }
                this.changeDot();
            };


            this.init();
        };

        return this.each(function()
        {
             new SlideSelf($(this), cfg);
        });
    }
})(jQuery);



$(".slide-box").slideSelf({
    "dots"          : true
});

$("#convert-tab").on("click",function(e){
    var id = $(e.target).attr("tab");
    if(id!=undefined){
        $(this).find("span").removeClass("active");
        $(e.target).addClass("active");
        console.log(id);
        $(".slide-box").hide();
        $("#"+id).show();
    }
})
