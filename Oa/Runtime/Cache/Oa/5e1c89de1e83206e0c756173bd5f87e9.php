<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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

<body class="easyui-layout">
	<!--System Top-->
	<div id="north" data-options="region: 'north'" style="height:100px;">
		
    		<div style="float:right;">
	<?php if(is_array($Apps)): foreach($Apps as $k=>$A): ?><a href="javascript:void(0);" data-url='<?php echo U("Oa/Public/getLeftMenu");?>' data-click='true' class="north-category" data-id="<?php echo ($A["id"]); ?>"><?php echo ($A["title"]); ?></a><?php endforeach; endif; ?>
	<a href="javascript:void(0);" data-click='false' class="north-category easyui-splitbutton" data-options="menu:'#toparea-user-info-box'">用户信息</a>
	<a href="javascript:void(0);" data-click='false' class="north-category easyui-splitbutton" data-options="menu:'#toparea-help-box'">系统帮助</a>
	<div id="toparea-user-info-box" style="display:none;">
		<div data-options="iconCls:'icon-user_suit'"><?php echo ($_SESSION['user']['username']); ?></div>
		<div class="menu-sep"></div>
		<div data-options="iconCls:'icon-user_comment'">个人信息</div>
		<div data-options="iconCls:'icon-user_edit'">修改密码</div>
		<div class="menu-sep"></div>
		<div data-options="iconCls:'icons-user-user_go'" onclick="OaModule.jump('<?php echo U('Oa/Login/logout');?>');">退出登录</div>
	</div>
	<div id="toparea-help-box" style="display:none;">
		<div onclick="window.open('http://www.0796z.com')">官方网站</div>
		<div onclick="$.messager.alert('问题反馈', '请发邮件到624508914@qq.com提交反馈，谢谢！', 'info');">问题反馈</div>
		<div class="menu-sep"></div>
		<div>更新缓存</div>
		<div>
			<span>切换主题</span>
			<div id="toparea-help-theme-box">
				<div onclick="OaModule.theme('default')">Default</div>
				<div onclick="OaModule.theme('bootstrap')">Bootstrap</div>
				<div onclick="OaModule.theme('gray')">Gray</div>
				<div onclick="OaModule.theme('metro')">Metro</div>
				<div onclick="OaModule.theme('black')">Black</div>
			</div>
		</div>
		<div class="menu-sep"></div>
		<div onclick="$.messager.alert('关于', '版本号：1.0.141007<br /><br />联系QQ：624508914', 'info');">关于</div>
	</div>
</div>
    	
    </div>
    <!--System Left-->
    <div id="west" data-options="region: 'west',title:'欢迎登陆Oa系统',split:true" style="width:150px;">
    	
    		<div id="leftmenu" class="easyui-accordion" data-options="fit:true,border:false"></div>
    	
    </div>
    <!--System Right-->
    <div id="center" data-options="region:'center'">
	    <div id="center_tabs">
        	<div title="系统首页">首页系统内容</div>
    	</div>
	</div>
    <div id="Global_div"></div>
	<script src="/Public/Js/Oa/main.js"></script>
</body>
</html>