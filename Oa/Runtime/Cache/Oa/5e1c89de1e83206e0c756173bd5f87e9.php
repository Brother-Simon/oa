<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="IE=edge" >
<title><?php echo ($page_title); ?></title>
<script src="../../../Public/EasyUi/jquery.min.js"></script>
<script src="../../../Public/Jquery/jquery.cookie.js"></script>
<script src="../../../Public/EasyUi/jquery.easyui.min.js"></script>
<script src="../../../Public/EasyUi/locale/easyui-lang-zh_CN.js"></script>
<link rel="stylesheet" href="../../../Public/EasyUi/themes/bootstrap/easyui.css" id="theme">
<link rel="stylesheet" href="../../../Public/EasyUi/themes/icon.css">
<link rel="stylesheet" href="../../../Public/Css/Oa/main.css">
</head>

<body class="easyui-layout">
	<!--System Top-->
	<div id="north" data-options="region: 'north'" style="height:100px;">
		
    		<div id="main-title"><?php echo (C("title")); ?></div>
<div id="main-module">
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
		<div onclick="$.messager.alert('关于', '版本号：1.0.141211<br /><br />联系QQ：624508914', 'info');">关于</div>
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
        	<div title="系统首页">
                <ul id="main_index">
                    <li>
                        <div class="easyui-panel" title="我的个人信息">
                            您好，<?php echo ($_SESSION['user']['username']); ?><br />
                            所属角色：<?php echo ($_SESSION['login_info']['groups']); ?><br />
                            最后登录时间：<?php echo ($_SESSION['login_info']['logindate']); ?><br />
                            最后登录IP：<?php echo ($_SESSION['login_info']['ip']); ?><br />
                            最后登录地点：<?php echo ($_SESSION['login_info']['logincity']); ?><br />
                        </div>
                    </li>
                    <li>
                        <div class="easyui-panel" title="安全提示信息">
                            网站上线后建议关闭DEBUG调试模式<br />
                            建议开启后台日志记录功能<br />
                            建议开启单点登录功能
                        </div>
                    </li>
                    <div class="clearfix"></div>
                    <li>
                        <div class="easyui-panel" title="服务器参数">
                            服务器域名/IP地址：<?php echo $_SERVER['HTTP_HOST'];?>(<?php echo $_SERVER['SERVER_ADDR'];?>) <br>
                            服务器标识：<?php echo php_uname();?> <br>
                            服务器操作系统：<?php echo php_uname('s');?><br>
                            服务器解译引擎：<?php echo $_SERVER['SERVER_SOFTWARE'];?> <br>
                            服务器语言：<?php echo $_SERVER['HTTP_ACCEPT_LANGUAGE'];?><br>
                            服务器端口：<?php echo $_SERVER['SERVER_PORT'];?> <br>
                            管理员邮箱：624508914@qq.com <br>
                            绝对路径：<?php echo $_SERVER['DOCUMENT_ROOT'];?><br>
                            上传文件最大限制（upload_max_filesize）：<?php echo ini_get('upload_max_filesize');?>
                        </div>
                    </li>
                    <li>
                        <div class="easyui-panel" title="系统说明">
                            版本号：1.0.141211[开发版] （联系QQ：624508914）<br>
                            本系统采用ThinkPHP 3.2.2 + jQuery easyUI 1.4.1 开发<br>
                            Github开源地址：https://github.com/624508914/oa<br>
                            二次开发参考手册：<br>
                            http://doc.thinkphp.cn/<br>
                            http://jeasyui.com/documentation/<br />
                            站长广告支持：<a href="http://www.0796z.com" target="_blank">吉安站</a><br />
                            点击链接加入群【医院OA系统-官方1群】：<a href="http://jq.qq.com/?_wv=1027&k=a99RHS" target="_blank">http://jq.qq.com/?_wv=1027&k=a99RHS</a>
                        </div>
                    </li>
                    <div class="clearfix"></div>
                    <li>
                        <div class="easyui-panel" title="更新日志">
                            [2014-12-14]<br />
                            1、后台医院设置->医院管理功能开发完成<br />
                            [2014-12-13]<br />
                            1、后台核心权限功能细节与样式优化<br />
                            [2014-12-11]<br />
                            1、后台核心权限功能开发完成
                        </div>
                    </li>
                    <li>
                        <div class="easyui-panel" title="支付宝捐赠">
                            <img src="/Public/Images/alipay.png" width="250" />
                        </div>
                    </li>
                </ul>
            </div>
    	</div>
	</div>
    <div id="Global_div"></div>
	<script src="/Public/Js/Oa/main.js"></script>
</body>
</html>