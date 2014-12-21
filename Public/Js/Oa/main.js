//新增OaModule全局对象
window.OaModule = {
	//登录默认提示
	tip: function(){
		$.messager.show({
			title:'登录提示',
			msg:'您好！wangdong 欢迎回来！<br/>最后登录时间：2014-10-07 15:51:16<br/>最后登录IP：183.11.250.165',
			timeout:5000,
			showType:'slide'
		});
	},
	//切换主题
	theme: function(t){
		var theme = t;
		$("#theme").attr("href","/Public/EasyUi/themes/"+theme+"/easyui.css");
		$.cookie('theme', theme, {path:'/', expires:3650});
	},
	//跳转函数
	jump: function(u){
		location.href = u;
	},
	//显示打开内容
	openUrl: function(node){
		if(node.type){
			if($("#center_tabs").tabs('exists', node.text)){
				$('#center_tabs').tabs('select', node.text);
			}else{
				$('#center_tabs').tabs('add',{
					title: node.text,
					href: node.url,
					closable: true,
					cache: true,
					tools:[{
				        iconCls:'icon-mini-refresh',
				        handler:function(){
				            var tab = $("#center_tabs").tabs("getSelected");
				            tab.panel("refresh",tab[0]['baseUrl']);
				        }
				    }]
				});
			}
		}
	},
	//移除左侧栏目 发现需要执行两次才能彻底清除
	removeLeftMenu: function(stop,titles){
		//加个判断，防止多次点击重复加载
		var options = $('body').layout('panel', 'west').panel('options');
		if(titles == options.title) return false;
		var leftmenu = $("#leftmenu").accordion("panels");
		$.each(leftmenu, function(i,n) {
			if(n){
				var t = n.panel("options").title;
				if(titles && titles.length){
					for(var k = 0; k < titles.length; k++){
						if(titles[k] == t) $("#leftmenu").accordion("remove", t);
					}
				}else{
					$("#leftmenu").accordion("remove", t);
				}
			}
		});
		var pp = $('#leftmenu').accordion('getSelected');
		if(pp) {
			var t = pp.panel('options').title;
			if(titles && titles.length){
				for(var k = 0; k < titles.length; k++){
					if(titles[k] == t) $("#leftmenu").accordion("remove", t);
				}
			}else{
				$("#leftmenu").accordion("remove", t);
			}
		}
		if(!stop){
			this.removeLeftMenu(true, titles);
		}

	}
}
$(function(){
	if($.cookie('theme')){
    	OaModule.theme($.cookie('theme'));
	}
	$(".north-category:eq(0)").click();
});
//实例化分类
$("#west-accordion").accordion({
	fit:true
});
//实例化选项卡
$("#center_tabs").tabs({
	fit:true,
	tabPosition:'bottom',
	border:false
});
//获取左侧菜单
$(".north-category").click(function(e) {
	if($(this).attr("data-click") == "true"){
		title = $(this).text();
		$(this).addClass("active").siblings().removeClass("active");
		$.ajax({
			type: 'POST',
			url: $(this).attr("data-url"),
			data: 'pid='+$(this).attr("data-id"),
			dataType: 'json',
			beforeSend: function(){
				$.messager.progress({text: "正在获取菜单列表，请稍后..."});
				//更新标题名称
				$('body').layout('panel', 'west').panel({title: title});
			},
			success: function(data){
				OaModule.removeLeftMenu();
				if(data){
					//左侧内容更新
					$.each(data, function(i,m) {
						var content = '';
						if(m.children){
							content = "<ul class='easyui-tree' data-options='data:"+ JSON.stringify(m.children) +",animate:true,lines:true,onClick:function(node){OaModule.openUrl(node)}'></ul>";
						}

						$("#leftmenu").accordion("add", {title: m.title, content: content});
					});
				} else {
					$("#leftmenu").accordion("add", {title:false, content: "暂无信息"});
				}
				$("#leftmenu").accordion("select",0);
				$.messager.progress("close");
			}
		});
	}
});