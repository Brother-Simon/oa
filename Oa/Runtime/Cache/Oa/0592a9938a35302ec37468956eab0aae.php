<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo ($page_title); ?></title>
<script src="../../../Public/EasyUi/jquery.min.js"></script>
<script src="../../../Public/Jquery/jquery.cookie.js"></script>
<script src="../../../Public/EasyUi/jquery.easyui.min.js"></script>
<script src="../../../Public/EasyUi/locale/easyui-lang-zh_CN.js"></script>
<link rel="stylesheet" href="../../../Public/EasyUi/themes/default/easyui.css" id="theme">
<link rel="stylesheet" href="../../../Public/EasyUi/themes/icon.css">
<link rel="stylesheet" href="../../../Public/Css/Oa/main.css">
</head>

<body>
<div id="login" class="easyui-dialog">
	<form action="" method="post" id="loginform">
    	<label>
        	<span>用户名：</span>
            <input type="text" name="username" class="easyui-textbox" />
        </label>
        <label>
        	<span>密&nbsp;&nbsp;码：</span>
            <input type="password" name="password" class="easyui-textbox" />
        </label>
        <label>
        	<span>验证码：</span>
            <input type="text" class="easyui-textbox" name="verify" style="width:80px;" />
            <img src="<?php echo U('Login/verify');?>" class="verify" />
        </label>
    </form>
</div>
<script>
$(".verify").on('click',function(){//验证码切换
	$(this).attr("src",$(this).attr("src")+"?"+(Math.floor(Math.random(100)*10)));
});

$("#loginform").on("submit",function(){
	$.ajax({
		type:$(this).attr("method"),
		url:$(this).attr("action"),
		data:$(this).serialize(),
		dataType:"json",
		beforeSend: function(){
			$.messager.progress({
				text:"正在校对账号密码信息，请稍后..."
			});
		},
		success: function(msg){
			$.messager.progress("close");
			if(msg.status == "error"){
				$.messager.show({
					title:'温馨提示',
					msg:msg.info,
					timeout:3000,
					showType:'slide',

				});
				$(".verify").click();
			} else {
				setTimeout("location.href='"+msg.url+"'",1000);
				$.messager.progress({
					text:"账号密码正确，正在跳转用户中心"
				});
			}
		}
	});
	return false;
});
$("#login").dialog({
	title		: '用户登录',
	width		: 300,
	height		: 240,
	draggable 	: false,
	modal		: false,
	closable	: false,
	buttons		: [{
			text 	: '登陆',
			iconCls	: 'icon-ok',
			handler	: function(){
				$("#loginform").submit();
			}
		}]
});
$(function(){
	$('form').focus();
	$('form').keyup(function(event){
		if(event.keyCode == 13) $("#loginform").submit();
	});
});
</script>
</body>
</html>