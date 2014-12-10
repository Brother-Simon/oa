<?php if (!defined('THINK_PATH')) exit();?><table id="System_group"></table>
<script type="text/javascript">
window.GroupModel = {
	/**
	 * [group 角色对象容器]
	 * @type {String}
	 */
	group: "#System_group",
	/**
	 * [global 全局模态框DIV]
	 * @type {String}
	 */
	global: "#Global_div",
	//工具栏
	toolbar: [
		{ text: '添加角色', iconCls: 'icon-add', handler: function(){GroupModel.add();} },
		{ text: '刷新', iconCls: 'icon-reload', handler: function(){GroupModel.refresh();} },
	],
	//右下角弹窗提示
	messager: function(info,title,time){
		if(title == null){
			title = "温馨提示";
		}
		if(time == ""){
			time = 1000;
		}
		$.messager.show({title:title,msg:info,timeout:time,showType:'slide'});
	},
	//刷新
	refresh: function(){
		$(this.group).datagrid("reload");
	},
	//状态格式化
	status: function(value){
		if(value == '1'){
			return "启用";
		} else {
			return "禁用";
		}
	},
	//操作格式化
	op: function(value){
		var btn = [];
		btn.push('<a href="javascript:;" onclick="GroupModel.edit('+value+')">修改</a>');
		btn.push('<a href="javascript:;" onclick="GroupModel.delete('+value+')">删除</a>');
		return btn.join(' | ');
	},
	//规则添加
	add: function(){
		$(this.global).dialog({
			title: '添加角色',
			width: 300,
			height: 200,
			iconCls: 'icon-add',
			href: '/index.php/Oa/System/addGroup/',
			modal: true,
			buttons: [{
				text:"添加",
				iconCls: 'icon-ok',
				handler:function(){
					$.messager.progress({text:'处理中，请稍候...'});
					$("#addGroup").form("submit",{
						url: "/index.php/Oa/System/addGroup/",
						ajax:true,
						onSubmit: function(){
							var isValid = $(this).form('validate');
							if (!isValid){
								$.messager.progress('close');	// 如果表单是无效的则隐藏进度条
							}
							return isValid;	// 返回false终止表单提交
						},
						success: function(msg){
							$.messager.progress('close');
							msg = $.parseJSON(msg);
							if(!msg.status){
								GroupModel.messager(msg.info);
							} else {
								$(GroupModel.global).dialog("close");
								GroupModel.messager(msg.info);
								GroupModel.refresh();
							}
						},
						queryParams: {
							addGroup: true,
						},
					});
				},
			},{
				text:"取消",
				iconCls:'icon-cancel',
				handler:function(){
					$(GroupModel.global).dialog("close");
				}
			}]
		});
	},
	//规则添加
	edit: function(id){
		$(this.global).dialog({
			title: '编辑角色',
			width: 300,
			height: 370,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/editGroup");?>/id/'+id,
			modal: true,
			buttons: [{
				text:"确认编辑",
				iconCls: 'icon-ok',
				handler:function(){
					$.messager.progress({text:'处理中，请稍候...'});
					$("#addGroup").form("submit",{
						url: '<?php echo U("Oa/System/editGroup");?>',
						ajax:true,
						onSubmit: function(){
							var isValid = $(this).form('validate');
							if (!isValid){
								$.messager.progress('close');	// 如果表单是无效的则隐藏进度条
							}
							return isValid;	// 返回false终止表单提交
						},
						success: function(msg){
							$.messager.progress('close');
							msg = $.parseJSON(msg);
							if(!msg.status){
								GroupModel.messager(msg.info);
							} else {
								$(GroupModel.global).dialog("close");
								GroupModel.messager(msg.info);
								GroupModel.refresh();
							}
						},
						queryParams: {
							editGroup: true,
						},
					});
				},
			},{
				text:"取消",
				iconCls:'icon-cancel',
				handler:function(){
					$(GroupModel.global).dialog("close");
				}
			}]
		});
	}

}
$("#System_group").datagrid({
	title: "当前位置："+$(".north-category.active").text()+" > "+$(".tabs-selected").text(),
	remoteSort:false,
	singleSelect:true,
	nowrap:false,
	border:false,
	fitColumns:true,
	fit:true,
	toolbar: GroupModel.toolbar,
	idField:'id',
	rownumbers:true,
	url:"<?php echo U('Oa/System/group');?>",
	sortable:true,
	columns:[[
		{field:'id',title:'角色ID',width:20,align:'center',sortable:true,},
		{field:'title',title:'角色名称',width:40},
		{field:'groupuser',title:'角色所含用户',width:380},
		{field:'status',title:'状态',width:20,formatter:GroupModel.status,},
		{field:'op',title:'操作',width:40,align:'right',formatter:GroupModel.op}
	]],
	pagination:true,
	pagePosition:'bottom',
	pageNumber:1,
	pageSize:20,
	pageList:[20,30,50]
});
</script>