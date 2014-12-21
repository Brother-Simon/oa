<?php if (!defined('THINK_PATH')) exit();?><table id="System_hospitalDoctor"></table>
<script type="text/javascript">
window.HospitalDoctorModel = {
	/**
	 * [hospitalDoctor 医生对象容器]
	 * @type {String}
	 */
	hospitalDoctor: "#System_hospitalDoctor",
	/**
	 * [global 全局模态框DIV]
	 * @type {String}
	 */
	global: "#Global_div",
	//工具栏
	toolbar: [
		{ text: '添加医生', iconCls: 'icon-add', handler: function(){HospitalDoctorModel.add();} },
		{ text: '刷新', iconCls: 'icon-reload', handler: function(){HospitalDoctorModel.refresh();} },
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
		btn.push('<a href="javascript:;" class="icon-block icon-page_white_edit" onclick="HospitalDoctorModel.edit('+value+')"> </a>');
		btn.push('<a href="javascript:;" class="icon-block icon-arrow_cross" onclick="HospitalDoctorModel.delete('+value+')"> </a>');
		return btn.join('');
	},
	//医生添加
	add: function(){
		$(this.global).dialog({
			title: '添加医生',
			width: 300,
			height: 200,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/addHospitalDoctor");?>',
			modal: true,
			buttons: [{
				text:"添加",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#addHospitalDoctor").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#addHospitalDoctor").attr("method"),
						url: $("#addHospitalDoctor").attr("action"),
						data:$("#addHospitalDoctor").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalDoctorModel.messager(msg.info);
							} else {
								$(HospitalDoctorModel.global).dialog("close");
								HospitalDoctorModel.messager(msg.info);
								HospitalDoctorModel.refresh();
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
					$(HospitalDoctorModel.global).dialog("close");
				}
			}]
		});
	},
	//医生添加
	edit: function(id){
		$(this.global).dialog({
			title: '编辑医生',
			width: 300,
			height: 220,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/editHospitalDoctor");?>/id/'+id,
			modal: true,
			buttons: [{
				text:"确认编辑",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#editHospitalDoctor").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#editHospitalDoctor").attr("method"),
						url: $("#editHospitalDoctor").attr("action"),
						data:$("#editHospitalDoctor").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalDoctorModel.messager(msg.info);
							} else {
								$(HospitalDoctorModel.global).dialog("close");
								HospitalDoctorModel.messager(msg.info);
								HospitalDoctorModel.refresh();
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
					$(HospitalDoctorModel.global).dialog("close");
				}
			}]
		});
	},
	/**
	 * [delete 删除医生]
	 * @return {[type]} [description]
	 */
	delete: function(id){
		$(this.global).dialog({
			title: "确认删除医生",
			width: 300,
			height: 120,
			iconCls: "icon-remove",
			modal: true,
			href: '<?php echo U("Oa/System/deleteHospitalDoctor");?>/id/'+id,
			buttons: [{
				text:"确认删除",
				iconCls: 'icon-ok',
				handler:function(){
					$.ajax({
						type: "post",
						url: "<?php echo U('Oa/System/deleteHospitalDoctor');?>",
						data:{id:id},
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalDoctorModel.messager(msg.info);
							} else {
								$(HospitalDoctorModel.global).dialog("close");
								HospitalDoctorModel.messager(msg.info);
								HospitalDoctorModel.refresh();
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
					$(HospitalDoctorModel.global).dialog("close");
				}
			}]
		});
	}

}
$("#System_hospitalDoctor").datagrid({
	title: "当前位置："+$(".north-category.active").text()+" > "+$(".tabs-selected").text(),
	remoteSort:false,
	singleSelect:true,
	nowrap:false,
	border:false,
	fitColumns:true,
	fit:true,
	toolbar: HospitalDoctorModel.toolbar,
	idField:'id',
	rownumbers:true,
	url:"<?php echo U('Oa/System/hospitalDoctor');?>",
	sortable:true,
	columns:[[
		{field:'id',title:'医生ID',width:20,align:'center',sortable:true,},
		{field:'title',title:'医生名称',width:80},
		{field:'doctor_number',title:'医生编号',width:80},
		{field:'hospital',title:'所属医院',width:380},
		{field:'op',title:'操作',width:40,align:'right',formatter:HospitalDoctorModel.op}
	]],
	pagination:true,
	pagePosition:'bottom',
	pageNumber:1,
	pageSize:20,
	pageList:[20,30,50]
});
</script>