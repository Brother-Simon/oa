<?php if (!defined('THINK_PATH')) exit();?><table id="System_user"></table>
<script type="text/javascript">
window.UserModel = {
	/**
	 * [user 用户对象容器]
	 * @type {String}
	 */
	user: "#System_user",
	/**
	 * [global 全局模态框DIV]
	 * @type {String}
	 */
	global: "#Global_div",
	//工具栏
	toolbar: [
		{ text: '添加用户', iconCls: 'icon-add', handler: function(){UserModel.add();} },
		{ text: '刷新', iconCls: 'icon-reload', handler: function(){UserModel.refresh();} },
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
		$(this.user).datagrid("reload");
	},
	//状态格式化
	status: function(value){
		if(value == '1'){
			return "<a href='javascript:;' class='icon-block icon-ok'>  </a>";
		} else {
			return "<a href='javascript:;' class='icon-block icon-no'> </a>";
		}
	},
	//操作格式化
	op: function(value){
		var btn = [];
		var btn = [];
		btn.push('<a href="javascript:;" class="icon-block icon-page_white_edit" onclick="UserModel.edit('+value+')"> </a>');
		btn.push('<a href="javascript:;" class="icon-block icon-arrow_cross" onclick="UserModel.delete('+value+')"> </a>');
		return btn.join('');
	},
	//规则添加
	add: function(){
		$(this.global).dialog({
			title: '添加用户',
			width: 300,
			height: 340,
			iconCls: 'icon-add',
			href: '/index.php/Oa/System/addUser/',
			modal: true,
			buttons: [{
				text:"添加",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#addUser").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#addUser").attr("method"),
						url: $("#addUser").attr("action"),
						data:$("#addUser").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								UserModel.messager(msg.info);
							} else {
								$(UserModel.global).dialog("close");
								UserModel.messager(msg.info);
								UserModel.refresh();
							}
						},
						error: function(){
							$.messager.progress("close");
						}
					});
				},
			},{
				text:"取消",
				iconCls:'icon-cancel',
				handler:function(){
					$(UserModel.global).dialog("close");
				}
			}]
		});
	},
	//规则添加
	edit: function(id){
		$(this.global).dialog({
			title: '编辑用户',
			width: 300,
			height: 370,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/editUser");?>/id/'+id,
			modal: true,
			buttons: [{
				text:"确认编辑",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#editUser").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#editUser").attr("method"),
						url: $("#editUser").attr("action"),
						data:$("#editUser").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								UserModel.messager(msg.info);
							} else {
								$(UserModel.global).dialog("close");
								UserModel.messager(msg.info);
								UserModel.refresh();
							}
						},
						error: function(){
							$.messager.progress("close");
						}
					});
				},
			},{
				text:"取消",
				iconCls:'icon-cancel',
				handler:function(){
					$(UserModel.global).dialog("close");
				}
			}]
		});
	},
	/**
	 * [delete 删除用户]
	 * @return {[type]} [description]
	 */
	delete: function(id){
		$(this.global).dialog({
			title: "确认删除规则",
			width: 300,
			height: 120,
			iconCls: "icon-remove",
			modal: true,
			href: '<?php echo U("Oa/System/deleteUser");?>'+'/id/'+id,
			buttons: [{
				text:"确认删除",
				iconCls: 'icon-ok',
				handler:function(){
					$.ajax({
						type: "post",
						url: "<?php echo U('Oa/System/deleteUser');?>",
						data:{id:id},
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								UserModel.messager(msg.info);
							} else {
								$(UserModel.global).dialog("close");
								UserModel.messager(msg.info);
								UserModel.refresh();
							}
						},
						error: function(){
							$.messager.progress("close");
						}
					});
				},
			},{
				text:"取消",
				iconCls:'icon-cancel',
				handler:function(){
					$(UserModel.global).dialog("close");
				}
			}]
		});
	}

}
$("#System_user").datagrid({
	title: "当前位置："+$(".north-category.active").text()+" > "+$(".tabs-selected").text(),
	remoteSort:false,
	singleSelect:true,
	nowrap:false,
	border:false,
	fitColumns:true,
	fit:true,
	toolbar: UserModel.toolbar,
	idField:'id',
	rownumbers:true,
	url:"<?php echo U('Oa/System/user');?>",
	sortable:true,
	columns:[[
		{field:'id',title:'用户ID',width:20,align:'center',sortable:true,},
		{field:'username',title:'用户名称',width:40},
		{field:'usergroup',title:'用户所属组',width:100},
		{field:'qq',title:'QQ号码',width:50},
		{field:'tel',title:'联系电话',width:50},
		{field:'logindate',title:'最后一次登录时间',width:50},
		{field:'loginnums',title:'登录次数',width:20},
		{field:'status',title:'状态',width:20,formatter:UserModel.status,},
		{field:'op',title:'操作',width:40,align:'right',formatter:UserModel.op}
	]],
	pagination:true,
	pagePosition:'bottom',
	pageNumber:1,
	pageSize:20,
	pageList:[20,30,50]
});
</script>