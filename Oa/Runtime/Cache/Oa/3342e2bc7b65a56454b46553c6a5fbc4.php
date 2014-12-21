<?php if (!defined('THINK_PATH')) exit();?><table id="System_hospitalDisease"></table>
<script type="text/javascript">
window.HospitalDiseaseModel = {
	/**
	 * [hospitalDisease 疾病对象容器]
	 * @type {String}
	 */
	hospitalDisease: "#System_hospitalDisease",
	/**
	 * [global 全局模态框DIV]
	 * @type {String}
	 */
	global: "#Global_div",
	//工具栏
	toolbar: [
		{ text: '添加疾病', iconCls: 'icon-add', handler: function(){HospitalDiseaseModel.add();} },
		{ text: '刷新', iconCls: 'icon-reload', handler: function(){HospitalDiseaseModel.refresh();} },
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
		btn.push('<a href="javascript:;" class="icon-block icon-page_white_edit" onclick="HospitalDiseaseModel.edit('+value+')"> </a>');
		btn.push('<a href="javascript:;" class="icon-block icon-arrow_cross" onclick="HospitalDiseaseModel.delete('+value+')"> </a>');
		return btn.join('');
	},
	//疾病添加
	add: function(){
		$(this.global).dialog({
			title: '添加疾病',
			width: 300,
			height: 170,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/addHospitalDisease");?>',
			modal: true,
			buttons: [{
				text:"添加",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#addHospitalDisease").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#addHospitalDisease").attr("method"),
						url: $("#addHospitalDisease").attr("action"),
						data:$("#addHospitalDisease").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalDiseaseModel.messager(msg.info);
							} else {
								$(HospitalDiseaseModel.global).dialog("close");
								HospitalDiseaseModel.messager(msg.info);
								HospitalDiseaseModel.refresh();
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
					$(HospitalDiseaseModel.global).dialog("close");
				}
			}]
		});
	},
	//疾病添加
	edit: function(id){
		$(this.global).dialog({
			title: '编辑疾病',
			width: 300,
			height: 220,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/editHospitalDisease");?>/id/'+id,
			modal: true,
			buttons: [{
				text:"确认编辑",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#editHospitalDisease").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#editHospitalDisease").attr("method"),
						url: $("#editHospitalDisease").attr("action"),
						data:$("#editHospitalDisease").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalDiseaseModel.messager(msg.info);
							} else {
								$(HospitalDiseaseModel.global).dialog("close");
								HospitalDiseaseModel.messager(msg.info);
								HospitalDiseaseModel.refresh();
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
					$(HospitalDiseaseModel.global).dialog("close");
				}
			}]
		});
	},
	/**
	 * [delete 删除疾病]
	 * @return {[type]} [description]
	 */
	delete: function(id){
		$(this.global).dialog({
			title: "确认删除疾病",
			width: 300,
			height: 120,
			iconCls: "icon-remove",
			modal: true,
			href: '<?php echo U("Oa/System/deleteHospitalDisease");?>/id/'+id,
			buttons: [{
				text:"确认删除",
				iconCls: 'icon-ok',
				handler:function(){
					$.ajax({
						type: "post",
						url: "<?php echo U('Oa/System/deleteHospitalDisease');?>",
						data:{id:id},
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalDiseaseModel.messager(msg.info);
							} else {
								$(HospitalDiseaseModel.global).dialog("close");
								HospitalDiseaseModel.messager(msg.info);
								HospitalDiseaseModel.refresh();
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
					$(HospitalDiseaseModel.global).dialog("close");
				}
			}]
		});
	}

}
$("#System_hospitalDisease").datagrid({
	title: "当前位置："+$(".north-category.active").text()+" > "+$(".tabs-selected").text(),
	remoteSort:false,
	singleSelect:true,
	nowrap:false,
	border:false,
	fitColumns:true,
	fit:true,
	toolbar: HospitalDiseaseModel.toolbar,
	idField:'id',
	rownumbers:true,
	url:"<?php echo U('Oa/System/hospitalDisease');?>",
	sortable:true,
	columns:[[
		{field:'id',title:'疾病ID',width:20,align:'center',sortable:true,},
		{field:'title',title:'疾病名称',width:80},
		{field:'hospital',title:'所属医院',width:380},
		{field:'op',title:'操作',width:40,align:'right',formatter:HospitalDiseaseModel.op}
	]],
	pagination:true,
	pagePosition:'bottom',
	pageNumber:1,
	pageSize:20,
	pageList:[20,30,50]
});
</script>