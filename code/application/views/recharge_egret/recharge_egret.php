<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" id="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width,height=device-height">
    <title></title>
    <style type="text/css">
        body{
            background-color: #EFEFF4;
            font-family: "Microsoft Yahei", Arial, "Helvetica Neue", sans-serif;
            margin: 10px 0 2px 0;
        }
        body>div{ background-color: #fff; color: #666;}
        #div_1{
            text-align: center;
            padding: 40px 0 40px 0;
            font-size: 16px;
            letter-spacing:2px;
        }
        #div_2{
            margin-top: 1px;
            height: 50px;
            line-height: 50px;
            background: url("http://www.ibl.cn/dest/z.png") #fff no-repeat 10px center;
            background-size: 20px;
            padding-left: 35px;
        }
        .btn{border:1px solid #EB3D60; color: #E6133C; border-radius: 3px; padding: 5px 10px 5px 10px; float: right; height: 20px; line-height: 20px; margin-top: 10px; margin-right: 10px;}
        .btn.activated{color: #fff; background-color: #E6133C;}
        .btn.disabled{ background-color: #CACACA; color: #fff; border-color: #CACACA;}
        #div_3{
            background-color: #fff;
            height: 50px;
            margin-top: 5px;
        }
        #namet{ color: #000;}
        #blcoin{ color: #000;}
        #my_blcoin{ color: #000;}
    </style>
</head>
<body>
<div id="div_1">
        确定使用<span id="blcoin"></span>百联币
        <br/>
        购买<span id="namet"></span>吗？
</div>
<div id="div_2">
    余额：<span id="my_blcoin"></span>
    <span class="btn" onclick="recharge()">立即充值</span>
</div>
<div id="div_3">
    <span class="btn" id="buy" onclick="buyok()">确认购买</span>
    <span class="btn" onclick="toback()">取消</span>
</div>
<script>
//    window.parent.gameAss.hide();
    var JCAjax = function(){
        this.xmlHttpReq = null,
                this.init = function(){
                    try {
                        this.xmlHttpReq = new ActiveXObject("Msxml2.XMLHTTP");//IE高版本创建XMLHTTP
                    }
                    catch(E) {
                        try {
                            this.xmlHttpReq  = new ActiveXObject("Microsoft.XMLHTTP");//IE低版本创建XMLHTTP
                        }
                        catch(E) {
                            this.xmlHttpReq  = new XMLHttpRequest();//兼容非IE浏览器，直接创建XMLHTTP对象
                        }
                    }
                },
                this.init(),
                this.open = function(method,url,async,func){
                    var that = this;
                    if (this.xmlHttpReq != null) {
                        try{
                            this.xmlHttpReq.open(method, url, async);
                            this.xmlHttpReq.onreadystatechange = function(){
                                if (that.xmlHttpReq.readyState== 4) {
                                    if (that.xmlHttpReq.status == 200) {
                                        func(that.xmlHttpReq.responseText);
                                    }
                                }
                            }; //指定响应函数
                            this.xmlHttpReq.send(null);
                        }catch(e){
                            func(false);
                            return false;
                        }
                    }else{
                        throw new Error("xmlHttpReq is null");
                    }
                }
    };

    function  getParam (param) {
        var reg = new RegExp("(^|&)" + param + "=([^&]*)(&|$)", "i");
        var url = window.location.href;
        url = url.substring(url.indexOf("?") + 1, url.length);
        var r = decodeURI(url).match(reg);
        r = r != null ? unescape(r[2]) : null;
        return r;
    };

    function  getParamFromParent (param) {
        var reg = new RegExp("(^|&)" + param + "=([^&]*)(&|$)", "i");
        var url =  window.parent.location.href;
        url = url.substring(url.indexOf("?") + 1, url.length);
        var r = decodeURI(url).match(reg);
        r = r != null ? unescape(r[2]) : null;
        return r;
    };



    var blcoin = document.getElementById("blcoin");
    blcoin.innerHTML = getParam("blcoin");

    var namet = document.getElementById("namet");
    namet.innerHTML =  getParam("name");

    var my_blcoin = document.getElementById("my_blcoin");
    my_blcoin.innerHTML = getParam("my_blcoin");

    var buy = document.getElementById("buy");
    if(getParam("is_buy")==="1"){
        buy.classList.add("activated");
    }else{
        buy.classList.add("disabled");
    }

    function buyok(){
      if(getParam("is_buy")==="0")return;
	   var url;
	 
		url="http://pcgame.ibl.cn/game/buy_prop?id="+getParam("id");
		console.log(url);
		var jcajax = new JCAjax();
		jcajax.open("GET",url,false,function(text){
			var json = JSON.parse(text);
			console.log(json);
			if(json.resCode==="00100000"){
				window.history.go(-1);
			}
		});
	

    }
    function toback(){
			window.history.go(-1);
		
    }

    function recharge(){
			window.parent.location.href = "http://pcgame.ibl.cn/pay/index";
		
    }


</script>
</body>
</html>