<?php if (!defined('THINK_PATH')) exit();?><table id="System_hospital"></table>
<script type="text/javascript">
window.HospitalModel = {
	/**
	 * [hospital 医院对象容器]
	 * @type {String}
	 */
	hospital: "#System_hospital",
	/**
	 * [global 全局模态框DIV]
	 * @type {String}
	 */
	global: "#Global_div",
	//工具栏
	toolbar: [
		{ text: '添加医院', iconCls: 'icon-add', handler: function(){HospitalModel.add();} },
		{ text: '刷新', iconCls: 'icon-reload', handler: function(){HospitalModel.refresh();} },
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
		btn.push('<a href="javascript:;" class="icon-block icon-page_white_edit" onclick="HospitalModel.edit('+value+')"> </a>');
		btn.push('<a href="javascript:;" class="icon-block icon-arrow_cross" onclick="HospitalModel.delete('+value+')"> </a>');
		return btn.join('');
	},
	//医院添加
	add: function(){
		$(this.global).dialog({
			title: '添加医院',
			width: 300,
			height: 220,
			iconCls: 'icon-add',
			href: '/index.php/Oa/System/addHospital/',
			modal: true,
			buttons: [{
				text:"添加",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#addHospital").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#addHospital").attr("method"),
						url: $("#addHospital").attr("action"),
						data:$("#addHospital").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalModel.messager(msg.info);
							} else {
								$(HospitalModel.global).dialog("close");
								HospitalModel.messager(msg.info);
								HospitalModel.refresh();
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
					$(HospitalModel.global).dialog("close");
				}
			}]
		});
	},
	//医院添加
	edit: function(id){
		$(this.global).dialog({
			title: '编辑医院',
			width: 300,
			height: 220,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/editHospital");?>/id/'+id,
			modal: true,
			buttons: [{
				text:"确认编辑",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#editHospital").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#editHospital").attr("method"),
						url: $("#editHospital").attr("action"),
						data:$("#editHospital").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalModel.messager(msg.info);
							} else {
								$(HospitalModel.global).dialog("close");
								HospitalModel.messager(msg.info);
								HospitalModel.refresh();
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
					$(HospitalModel.global).dialog("close");
				}
			}]
		});
	},
	/**
	 * [delete 删除医院]
	 * @return {[type]} [description]
	 */
	delete: function(id){
		$(this.global).dialog({
			title: "确认删除医院",
			width: 300,
			height: 120,
			iconCls: "icon-remove",
			modal: true,
			href: '<?php echo U("Oa/System/deleteHospital");?>/id/'+id,
			buttons: [{
				text:"确认删除",
				iconCls: 'icon-ok',
				handler:function(){
					$.ajax({
						type: "post",
						url: "<?php echo U('Oa/System/deleteHospital');?>",
						data:{id:id},
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalModel.messager(msg.info);
							} else {
								$(HospitalModel.global).dialog("close");
								HospitalModel.messager(msg.info);
								HospitalModel.refresh();
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
					$(HospitalModel.global).dialog("close");
				}
			}]
		});
	}

}
$("#System_hospital").datagrid({
	title: "当前位置："+$(".north-category.active").text()+" > "+$(".tabs-selected").text(),
	remoteSort:false,
	singleSelect:true,
	nowrap:false,
	border:false,
	fitColumns:true,
	fit:true,
	toolbar: HospitalModel.toolbar,
	idField:'id',
	rownumbers:true,
	url:"<?php echo U('Oa/System/hospital');?>",
	sortable:true,
	columns:[[
		{field:'id',title:'医院ID',width:20,align:'center',sortable:true,},
		{field:'title',title:'医院名称',width:80},
		{field:'description',title:'医院描述',width:380},
		{field:'status',title:'状态',width:20,formatter:HospitalModel.status,},
		{field:'op',title:'操作',width:40,align:'right',formatter:HospitalModel.op}
	]],
	pagination:true,
	pagePosition:'bottom',
	pageNumber:1,
	pageSize:20,
	pageList:[20,30,50]
});
</script>