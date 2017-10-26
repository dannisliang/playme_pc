/**
 * Created by xuzhixiong on 2016/12/28.
 */
(function($){
    var recharge=function(){
        var text=null;
        var balance=null;
        var RMB=null;
        this.sum=null;
        this.pay=null;
        var recharge_money=null;


        //sum是当前积分
        this.init=function(sum){
            this.sum=sum;
            text=parseInt($("#integration").val());
            balance=sum-text;
            RMB=(text/100).toFixed(2);
            recharge_money=5;
            this.shouldPay();
            $("#balance").text(balance);
            $("#gold").text(text);
            $("#RMB").text(RMB);


            this.addClick();
        }

        this.addClick=function(){
            var that1=this;

            $(".detail_top span").bind("click",function(){
                $(this).addClass("span_selected").siblings("span").removeClass("span_selected");
                $("#checkbox").attr("checked",false);
                $("#integration").val(0);
                $("#ziDing").val("");
                $("#ziDing").attr("placeholder","自定义");
                recharge_money=$(this).text()/100;
                that1.checkBig();
                that1.money_account();
                that1.shouldPay();
            })


            $("#calculate span").bind("click",function(){
                var that=this;
                that1.integral_account(that);
                that1.money_account();
                that1.shouldPay();
            })

            $("#checkbox").change(function(){
                that1.ifCheck();
            })



            $("#ziDing").focus(function(){
                that1.inputOn();
                $("#checkbox").attr("checked",false);
                $("#integration").val(0);
                $(this).attr("placeholder","");
                recharge_money=0;
                that1.checkBig();
                that1.money_account();
                that1.shouldPay();
            })
            $("#ziDing").blur(function(){
                $(this).attr("placeholder","自定义");
                that1.getVal();
                that1.checkBig();
                that1.money_account();
                that1.shouldPay();
            })

            $("#integration").focus(function(){
                if($("#integration").val()==0)$("#integration").val("");
                if($("#checkbox").attr("checked")){
                    $("#checkbox").attr("checked",false);
                }
            })

            $("#integration").blur(function(){
                var temp;
                that1.checkBig();
                that1.money_account();
                that1.shouldPay();
            })

        }

        this.integral_account=function(that){
            text=parseInt($("#integration").val());
            var balance;
            var temp;
            switch($(that)[0].id){
                case "jian1":
                    if($("#checkbox").attr("checked")){
                        $("#checkbox").attr("checked",false);
                    }
                    if(text>=10){
                        text-=10;
                    }
                    break;
                case "jia1":
                    this.sum>recharge_money*100? temp=recharge_money*100:temp=this.sum;
                    if(text<=temp-10){
                        text+=10;
                    }
                    break;
            }
            balance=this.sum-text;
            $("#integration").val(text);
            $("#balance").text(balance);
        }

        this.money_account=function(){
            $("#balance").text(this.sum-text);
            RMB=(text/100).toFixed(2);
            $("#gold").text(text);
            $("#RMB").text(RMB);
        }

        this.ifCheck=function(){
            if($("#checkbox").attr("checked")){
                if(recharge_money*100>=this.sum){
                    this.checkAfter(this.sum);
                }else{
                    this.checkAfter(recharge_money*100);
                }
                this.shouldPay();
            }
        }

        this.shouldPay=function(){
            this.pay=(recharge_money-RMB).toFixed(2)+"";
            var payArray=this.pay.split(".");
            $("#integer").text(payArray[0]);
            $("#decimal").text(payArray[1]);
        }

        this.inputOn=function(){
            $(".detail_top span").each(function(){
                $(this).removeClass("span_selected");
            })
            $("#ziDing").css("border","1px solid #e6133c");
        }

        this.getVal=function(){
            var val=($("#ziDing").val()/100).toFixed(2);
            recharge_money=val;
        }

        this.checkBig=function(){
            text=parseInt($("#integration").val());
            if(!text){text=0;$("#integration").val(0)}
            if(text>this.sum){
                text=this.sum;$("#integration").val(this.sum)
            }
            if((recharge_money*100-text)<0){text=recharge_money*100;$("#integration").val(recharge_money*100)}
        }

        this.checkAfter=function(sum){
            $("#balance").text(this.sum-sum);
            $("#integration").val(sum);
            $("#gold").text(sum);
            RMB=(sum/100).toFixed(2);
            $("#RMB").text(RMB);
        }
    }
   var reCharge=new recharge();
    reCharge.init($("#balance").text());
})(jQuery)