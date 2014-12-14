<?php if (!defined('THINK_PATH')) exit();?><div id='System_rule'>dsfsdf</div>
<script>
window.RuleModel = {
	/**
	 * [rule 规则对象容器]
	 * @type {String}
	 */
	rule: "#System_rule",
	/**
	 * [global 全局模态框DIV]
	 * @type {String}
	 */
	global: "#Global_div",
	/**
	 * [toolbar 工具栏]
	 * @type {Array}
	 */
	toolbar: [
		{ text: '添加', iconCls: 'icon-add', handler: function(){RuleModel.add();} },
		{ text: '排序', iconCls: 'icon-arrow_merge', handler: function(){RuleModel.order();} },
		{ text: '刷新', iconCls: 'icon-reload', handler: function(){RuleModel.refresh();} },
	],
	/**
	 * [cls 样式字段格式化]
	 * @param  {[String]} value [字段值]
	 * @return {[type]}       [description]
	 */
	cls: function(value){
		return "<span class='"+value+"'></span>";
	},
	/**
	 * [type 模式字段格式化]
	 * @param  {[int]} value [字段值]
	 * @return {[type]}       [description]
	 */
	type: function(value){
		if(value == '1'){
			return "URL模式";
		} else {
			return "菜单模式";
		}
	},
	/**
	 * [status 状态字段格式化]
	 * @param  {[int]} value [字段值]
	 * @return {[type]}       [description]
	 */
	status: function(value){
		if(value == '1'){
			return "<a href='javascript:;' class='icon-block icon-ok'>  </a>";
		} else {
			return "<a href='javascript:;' class='icon-block icon-no'> </a>";
		}
	},
	/**
	 * [isshow 显示字段格式化]
	 * @param  {[int]}  value [字段值]
	 * @return {Boolean}       [description]
	 */
	isshow: function(value){
		if(value == '1'){
			return "<a href='javascript:;' class='icon-block icon-ok'>  </a>";
		} else {
			return "<a href='javascript:;' class='icon-block icon-no'> </a>";
		}
	},
	/**
	 * [sort 排序字段格式化]
	 * @param  {[int]} value [数据ID]
	 * @param  {[object]} row   [数据行对象]
	 * @return {[type]}       [description]
	 */
	sort: function(value,row){
		return "<input type='text' class='sort-input' name='order["+row['id']+"]' value='"+value+"' style='width:20px;text-align:center;' />";
	},
	/**
	 * [op 操作字段格式化]
	 * @param  {[Int]} value [数据ID]
	 * @return {[type]}       [description]
	 */
	op: function(value){
		var btn = [];
		btn.push('<a href="javascript:;" class="icon-block icon-arrow_add" onclick="RuleModel.add('+value+')"></a>');
		btn.push('<a href="javascript:;" class="icon-block icon-page_white_edit" onclick="RuleModel.edit('+value+')"></a>');
		btn.push('<a href="javascript:;" class="icon-block icon-arrow_cross" onclick="RuleModel.delete('+value+')"></a>');
		return btn.join('');
	},
	/**
	 * [messager 公用弹窗]
	 * @param  {[String]} info  [显示内容]
	 * @param  {[String]} title [标题]
	 * @param  {[Int]} time  [时间]
	 * @return {[type]}       [description]
	 */
	messager: function(info,title,time){
		if(title == null){
			title = "温馨提示";
		}
		if(time == ""){
			time = 1000;
		}
		$.messager.show({title:title,msg:info,timeout:time,showType:'slide'});
	},
	/**
	 * [refresh 规则刷新]
	 * @return {[type]} [description]
	 */
	refresh: function(){
		$(this.rule).treegrid("reload");
	},
	/**
	 * [order 规则排序]
	 * @return {[type]} [description]
	 */
	order: function(){
		$.post("<?php echo U('Oa/System/sortRule');?>",$('.sort-input').serialize(),function(msg){
			$.messager.progress("close");
			if(msg.status){
            	RuleModel.messager("排序更新成功");
            	RuleModel.refresh();
			} else{
				RuleModel.messager("排序更新失败");
			}
		});
	},
	/**
	 * [add 添加规则]
	 * @param {[type]} pid [description]
	 */
	add: function(pid){
		var addUrl = '<?php echo U("Oa/System/addRule/");?>';
		$(this.global).dialog({
			title: '添加规则',
			width: 300,
			height: 370,
			iconCls: 'icon-add',
			href: addUrl+"/pid/"+pid,
			modal: true,
			buttons: [{
				text:"添加",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#addRule").form('validate');
					if (!isValid){
						$.messager.progress('close');	// 如果表单是无效的则隐藏进度条
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#addRule").attr("method"),
						url: $("#addRule").attr("action"),
						data:$("#addRule").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								RuleModel.messager(msg.info);
							} else {
								$(RuleModel.global).dialog("close");
								RuleModel.messager(msg.info);
								RuleModel.refresh();
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
					$(RuleModel.global).dialog("close");
				}
			}]
		});
	},
	edit: function(id){
		var editUrl = '<?php echo U("Oa/System/editRule/");?>';
		$(this.global).dialog({
			title: '编辑规则',
			width: 300,
			height: 370,
			iconCls: 'icon-edit',
			href: editUrl+"/id/"+id,
			modal: true,
			buttons: [{
				text:"确认编辑",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#editRule").form('validate');
					if (!isValid){
						$.messager.progress('close');	// 如果表单是无效的则隐藏进度条
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#editRule").attr("method"),
						url: $("#editRule").attr("action"),
						data:$("#editRule").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								RuleModel.messager(msg.info);
							} else {
								$(RuleModel.global).dialog("close");
								RuleModel.messager(msg.info);
								RuleModel.refresh();
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
					$(RuleModel.global).dialog("close");
				}
			}]
		});
	},
	/**
	 * [delete 删除规则]
	 * @return {[type]} [description]
	 */
	delete: function(id){
		$(this.global).dialog({
			title: "确认删除规则",
			width: 300,
			height: 120,
			iconCls: "icon-remove",
			modal: true,
			href: '<?php echo U("Oa/System/deleteRule");?>'+'/id/'+id,
			buttons: [{
				text:"确认删除",
				iconCls: 'icon-ok',
				handler:function(){
					$.ajax({
						type: "post",
						url: "<?php echo U('Oa/System/deleteRule');?>",
						data:{id:id},
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								RuleModel.messager(msg.info);
							} else {
								$(RuleModel.global).dialog("close");
								RuleModel.messager(msg.info);
								RuleModel.refresh();
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
					$(RuleModel.global).dialog("close");
				}
			}]
		});
	}


}
$("#System_rule").treegrid({
	title: "当前位置："+$(".north-category.active").text()+" > "+$(".tabs-selected").text(),
	nowrap: false,
	border: false,
	fitColumns: true,
	fit: true,
	toolbar: RuleModel.toolbar,
	idField: 'id',
	treeField: 'title',
	rownumbers: true,
	animate: true,
	sortable: true,
	url: "<?php echo U('Oa/System/rule');?>",
	queryParams: {
		action: 'getList'
	},
	columns:[[
		{field:'sort',title:'排序',width:20,align:'center',sortable:true,formatter:RuleModel.sort,},
		{field:'id',title:'规则ID',width:20,align:'center'},
		{field:'title',title:'规则名称',width:80,},
		{field:'name',title:'规则标识',width:80,},
		{field:'condition',title:'条件',width:80,formatter:RuleModel.contains,},
		{field:'type',title:'类型',width:30,formatter:RuleModel.type,},
		{field:'status',title:'状态',width:20,formatter:RuleModel.status,},
		{field:'isshow',title:'是否显示',width:20,formatter:RuleModel.isshow,},
		{field:'cls',title:'图标',width:25,formatter:RuleModel.cls},
		{field:'op',title:'操作',width:60,align:'right',formatter:RuleModel.op}
	]]
});
</script>