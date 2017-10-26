/**
 * Created by xuzhixiong on 2017/1/6.
 */
(function($){
    var game_detail=function(){
        var width=$("#gameDetail-show").width();
        var length=$("#detail-slide .detail-page").length;
        this.nowindex=0;
        this.init=function(){
            $("#detail-slide").width(width*length);
            $("#detail-slide .detail-page").each(function(index){
                $(this).css("left",index*width);
            })
            this.addClick();
            //this.canPlay();
        }

        this.addClick=function(){
            var that=this;
            var temp=0;

            $(".rotate").bind('click',function(e){
                if($(this).parent().attr("isRotate")=="true"){
                    $(this).parent().attr("isRotate","false");
                    $(this).parent().css("transform","rotate(0deg)");
                }else{
                    $(this).parent().attr("isRotate","true");
                    $(this).parent().css("transform","rotate(-90deg)");
                }
            });

            $("#detail-left").on("click",function(){
                that.nowindex--;
                if(that.nowindex>=0) {
                    $("#detail-slide .detail-page").eq(that.nowindex).show();
                    $("#detail-slide").css("left",-that.nowindex*width);
                    if(that.nowindex==0){
                        $(this).css({"cursor":"text","background-image":"url('/img/left2.png')"});
                    }
                }else{
                    that.nowindex=0;
                }
            })

            $("#detail-right").on("click",function(){
                that.nowindex++;
                if(that.nowindex<=length-1){
                    $("#detail-slide .detail-page").eq(that.nowindex).show();
                    $("#detail-slide").css("left",-that.nowindex*width);
                    if(that.nowindex==length-1){
                        $(this).css("cursor","text"),$(this).css("background-image","url('/img/right2.png')")
                    }
               }else{
                    that.nowindex=length-1;
                }
            })

//            $("#collect").on("click",function(){
//                temp++;
//                temp%2==1?$(this).attr("src","img/collect.png"):$(this).attr("src","/img/uncollect.png");
//            })

            $("#detail-left").hover(function(){
                that.nowindex==0? ($(this).css("cursor","text"),$(this).css("background-image","url('/img/left2.png')")):
                    ($(this).css("cursor","pointer"),$(this).css("background-image","url('/img/left3.png')"))
            },function(){
                $(this).css("background-image","url('/img/left2.png')");
            })

            $("#detail-right").hover(function(){
                that.nowindex==length-1? ($(this).css("cursor","text"),$(this).css("background-image","url('/img/right2.png')")):
                    ($(this).css("cursor","pointer"),$(this).css("background-image","url('/img/right3.png')"))
            },function(){
                $(this).css("background-image","url('/img/right2.png')");
            })
        }

        this.canPlay=function(){
            $("#buy-confirm").on("click",function(){
                $(".kaishi>button").text("开始游戏");
                $(".kaishi>button").css({"background-color":"#eb454f",color:"#ffffff"});
                $(".kaishi>button").attr("popup-id","play_popup");
                $(".kaishi>button").hover(function(){
                    $(".kaishi>button").css({"background-color":"#c01133"});
                },function(){
                    $(".kaishi>button").css({"background-color":"#eb454f"});
                })
            })
        }
    }

    var gameDetail=new game_detail();
    gameDetail.init();
})(jQuery)