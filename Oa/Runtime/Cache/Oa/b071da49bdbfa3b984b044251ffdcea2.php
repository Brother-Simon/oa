<?php if (!defined('THINK_PATH')) exit();?><div style='word-break:break-all;'>
	<form id='addHospitalSite' method='post' action="<?php echo U('Oa/System/addHospitalSite');?>">
		<div style='width:100%;height:30px;'>
			<span>站点名称：</span>
			<input id='HospitalSite-title' name='title' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>站点网址：</span>
			<input id='HospitalSite-url' name='url' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>所属站点：</span>
			<input id='HospitalSite-hid' name='hid[]' />
		</div>
		<input type='hidden' name='addHospitalSite' value='addHospitalSite' />
	</form>
</div>
<script type="text/javascript">
$("#HospitalSite-title").textbox({
    required: true,
    validType:['length[2,40]'],
    missingMessage: "必须填写站点名称",
    invalidMessage: "已经存在该站点",
});
$("#HospitalSite-url").textbox({
    required: true,
    validType:['length[2,40]'],
    missingMessage: "必须填写站点网址",
    invalidMessage: "已经存在该站点",
});
$("#HospitalSite-hid").combotree({
	width: 192,
	checkbox:true,
	multiple:true,
	required: true,
	validType:['length[2,400]'],
	url: '<?php echo U("Oa/Common/treeHospital");?>',
	missingMessage: "必须选择站点所属医院",
});
</script>