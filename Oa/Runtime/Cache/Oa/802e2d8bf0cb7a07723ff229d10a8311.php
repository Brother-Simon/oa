<?php if (!defined('THINK_PATH')) exit();?><table id="System_hospitalSite"></table>
<script type="text/javascript">
window.HospitalSiteModel = {
	/**
	 * [hospitalSite 站点对象容器]
	 * @type {String}
	 */
	hospitalSite: "#System_hospitalSite",
	/**
	 * [global 全局模态框DIV]
	 * @type {String}
	 */
	global: "#Global_div",
	//工具栏
	toolbar: [
		{ text: '添加站点', iconCls: 'icon-add', handler: function(){HospitalSiteModel.add();} },
		{ text: '刷新', iconCls: 'icon-reload', handler: function(){HospitalSiteModel.refresh();} },
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
	url: function(value){
		var btn = '<a href="http://'+value+'" target="_blank">'+value+'</a>';
		return btn;
	},
	//操作格式化
	op: function(value){
		var btn = [];
		btn.push('<a href="javascript:;" class="icon-block icon-page_white_edit" onclick="HospitalSiteModel.edit('+value+')"> </a>');
		btn.push('<a href="javascript:;" class="icon-block icon-arrow_cross" onclick="HospitalSiteModel.delete('+value+')"> </a>');
		return btn.join('');
	},
	//站点添加
	add: function(){
		$(this.global).dialog({
			title: '添加站点',
			width: 300,
			height: 200,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/addHospitalSite");?>',
			modal: true,
			buttons: [{
				text:"添加",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#addHospitalSite").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#addHospitalSite").attr("method"),
						url: $("#addHospitalSite").attr("action"),
						data:$("#addHospitalSite").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalSiteModel.messager(msg.info);
							} else {
								$(HospitalSiteModel.global).dialog("close");
								HospitalSiteModel.messager(msg.info);
								HospitalSiteModel.refresh();
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
					$(HospitalSiteModel.global).dialog("close");
				}
			}]
		});
	},
	//站点添加
	edit: function(id){
		$(this.global).dialog({
			title: '编辑站点',
			width: 300,
			height: 220,
			iconCls: 'icon-add',
			href: '<?php echo U("Oa/System/editHospitalSite");?>/id/'+id,
			modal: true,
			buttons: [{
				text:"确认编辑",
				iconCls: 'icon-ok',
				handler:function(){
					var isValid = $("#editHospitalSite").form('validate');
					if (!isValid){
						return isValid;	// 返回false终止表单提交
					}
					$.ajax({
						type: $("#editHospitalSite").attr("method"),
						url: $("#editHospitalSite").attr("action"),
						data:$("#editHospitalSite").serialize(),
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalSiteModel.messager(msg.info);
							} else {
								$(HospitalSiteModel.global).dialog("close");
								HospitalSiteModel.messager(msg.info);
								HospitalSiteModel.refresh();
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
					$(HospitalSiteModel.global).dialog("close");
				}
			}]
		});
	},
	/**
	 * [delete 删除站点]
	 * @return {[type]} [description]
	 */
	delete: function(id){
		$(this.global).dialog({
			title: "确认删除站点",
			width: 300,
			height: 120,
			iconCls: "icon-remove",
			modal: true,
			href: '<?php echo U("Oa/System/deleteHospitalSite");?>/id/'+id,
			buttons: [{
				text:"确认删除",
				iconCls: 'icon-ok',
				handler:function(){
					$.ajax({
						type: "post",
						url: "<?php echo U('Oa/System/deleteHospitalSite');?>",
						data:{id:id},
						dataType:"json",
						beforeSend: function(){
							$.messager.progress({text:'处理中，请稍候...'});
						},
						success: function(msg){
							$.messager.progress("close");
							if(!msg.status){
								$.messager.progress('close');
								HospitalSiteModel.messager(msg.info);
							} else {
								$(HospitalSiteModel.global).dialog("close");
								HospitalSiteModel.messager(msg.info);
								HospitalSiteModel.refresh();
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
					$(HospitalSiteModel.global).dialog("close");
				}
			}]
		});
	}

}
$("#System_hospitalSite").datagrid({
	title: "当前位置："+$(".north-category.active").text()+" > "+$(".tabs-selected").text(),
	remoteSort:false,
	singleSelect:true,
	nowrap:false,
	border:false,
	fitColumns:true,
	fit:true,
	toolbar: HospitalSiteModel.toolbar,
	idField:'id',
	rownumbers:true,
	url:"<?php echo U('Oa/System/hospitalSite');?>",
	sortable:true,
	columns:[[
		{field:'id',title:'站点ID',width:20,align:'center',sortable:true,},
		{field:'title',title:'站点名称',width:80},
		{field:'url',title:'站点网址',width:80,formatter:HospitalSiteModel.url},
		{field:'hospital',title:'所属医院',width:380},
		{field:'op',title:'操作',width:40,align:'right',formatter:HospitalSiteModel.op}
	]],
	pagination:true,
	pagePosition:'bottom',
	pageNumber:1,
	pageSize:20,
	pageList:[20,30,50]
});
</script>