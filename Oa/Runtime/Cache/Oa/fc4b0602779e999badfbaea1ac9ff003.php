<?php if (!defined('THINK_PATH')) exit();?><div style='word-break:break-all;'>
	<form id='editHospitalDepartment' method='post' action="<?php echo U('Oa/System/editHospitalDepartment');?>">
		<div style='width:100%;height:30px;'>
			<span>科室名称：</span>
			<input id='HospitalDepartment-title' name='title' value="<?php echo ($HospitalDepartment["title"]); ?>" />
		</div>
		<div style='width:100%;height:30px;'>
			<span>所属科室：</span>
			<input id='HospitalDepartment-hid' name='hid[]' />
		</div>
		<input type='hidden' name='editHospitalDepartment' value='editHospitalDepartment' />
		<input type='hidden' name='id' value='<?php echo ($HospitalDepartment["id"]); ?>' />
	</form>
</div>
<script type="text/javascript">
$("#HospitalDepartment-title").textbox({
    required: true,
    validType:['length[2,40]'],
    missingMessage: "必须填写科室名称",
    invalidMessage: "已经存在该科室",
});
$("#HospitalDepartment-hid").combotree({
	width: 192,
	checkbox:true,
	multiple:true,
	required: true,
	validType:['length[2,400]'],
	url: '<?php echo U("Oa/Common/treeHospital");?>',
	value: '<?php echo ($HospitalDepartment["hid"]); ?>',
	missingMessage: "必须选择科室所属医院"
});
</script>