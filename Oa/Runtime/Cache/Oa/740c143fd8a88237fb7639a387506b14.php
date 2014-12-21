<?php if (!defined('THINK_PATH')) exit();?><table id="System_hospitalDepartment"></table>
<script type="text/javascript">
window.HospitalDepartmentModel = {
	/**
	 * [hospitalDepartment 科室对象容器]
	 * @type {String}
	 */
	hospitalDepartment: "#System_hospitalDepartment",
	/**
	 * [global 全局模态框DIV]
	 * @type {String}
	 */
	global: "#Global_div",
	//工具栏
	toolbar: [
		{ text: '添加科室', iconCls: 'icon-add', handler: function(){HospitalDepartmentModel.add();} },
		{ text: '刷新', iconCls: 'icon-reload', handler: function(){HospitalDepartmentModel.refresh();} },
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
		var tab = $("#center_tabs").tabs("getSelected");
		tab.panel("refresh",tab[0]['baseUrl']);
	},
	//操作格式化
	op: function(value){
		var btn = [];
		btn.push('<a href="javascript:;" class="icon-block icon-page_white_edit" onclick="HospitalDepartmentModel.edit('+value+')"> </a>');
		btn.push('<a href="javascript:;" class="icon-block icon-arrow_cross" onclick="HospitalDepartmentModel.delete('+value+')"> </a>');
		return btn.join('');
	},
	//科室添加
	add: function(){
		$(this.global).dialog({
			title: '添加科室',
			width: 300,
			height: 170,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/addHospitalDepartment");?>',
			modal: true,
			buttons: [{
				text:"添加",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#addHospitalDepartment").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#addHospitalDepartment").attr("method"),
						url: $("#addHospitalDepartment").attr("action"),
						data:$("#addHospitalDepartment").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalDepartmentModel.messager(msg.info);
							} else {
								$(HospitalDepartmentModel.global).dialog("close");
								HospitalDepartmentModel.messager(msg.info);
								HospitalDepartmentModel.refresh();
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
					$(HospitalDepartmentModel.global).dialog("close");
				}
			}]
		});
	},
	//科室添加
	edit: function(id){
		$(this.global).dialog({
			title: '编辑科室',
			width: 300,
			height: 220,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/editHospitalDepartment");?>/id/'+id,
			modal: true,
			buttons: [{
				text:"确认编辑",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#editHospitalDepartment").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#editHospitalDepartment").attr("method"),
						url: $("#editHospitalDepartment").attr("action"),
						data:$("#editHospitalDepartment").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalDepartmentModel.messager(msg.info);
							} else {
								$(HospitalDepartmentModel.global).dialog("close");
								HospitalDepartmentModel.messager(msg.info);
								HospitalDepartmentModel.refresh();
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
					$(HospitalDepartmentModel.global).dialog("close");
				}
			}]
		});
	},
	/**
	 * [delete 删除科室]
	 * @return {[type]} [description]
	 */
	delete: function(id){
		$(this.global).dialog({
			title: "确认删除科室",
			width: 300,
			height: 120,
			iconCls: "icon-remove",
			modal: true,
			href: '<?php echo U("Oa/System/deleteHospitalDepartment");?>/id/'+id,
			buttons: [{
				text:"确认删除",
				iconCls: 'icon-ok',
				handler:function(){
					$.ajax({
						type: "post",
						url: "<?php echo U('Oa/System/deleteHospitalDepartment');?>",
						data:{id:id},
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalDepartmentModel.messager(msg.info);
							} else {
								$(HospitalDepartmentModel.global).dialog("close");
								HospitalDepartmentModel.messager(msg.info);
								HospitalDepartmentModel.refresh();
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
					$(HospitalDepartmentModel.global).dialog("close");
				}
			}]
		});
	}

}
$("#System_hospitalDepartment").datagrid({
	title: "当前位置："+$(".north-category.active").text()+" > "+$(".tabs-selected").text(),
	remoteSort:false,
	singleSelect:true,
	nowrap:false,
	border:false,
	fitColumns:true,
	fit:true,
	toolbar: HospitalDepartmentModel.toolbar,
	idField:'id',
	rownumbers:true,
	url:"<?php echo U('Oa/System/hospitalDepartment');?>",
	sortable:true,
	columns:[[
		{field:'id',title:'科室ID',width:20,align:'center',sortable:true,},
		{field:'title',title:'科室名称',width:80},
		{field:'hospital',title:'所属医院',width:380},
		{field:'op',title:'操作',width:40,align:'right',formatter:HospitalDepartmentModel.op}
	]],
	pagination:true,
	pagePosition:'bottom',
	pageNumber:1,
	pageSize:20,
	pageList:[20,30,50]
});
</script>