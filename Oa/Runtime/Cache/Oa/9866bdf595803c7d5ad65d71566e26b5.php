<?php if (!defined('THINK_PATH')) exit();?><div style='word-break:break-all;'>
	<form id='editHospitalDoctor' method='post' action="<?php echo U('Oa/System/editHospitalDoctor');?>">
		<div style='width:100%;height:30px;'>
			<span>医生名称：</span>
			<input id='HospitalDoctor-title' name='title' value="<?php echo ($HospitalDoctor["title"]); ?>" />
		</div>
		<div style='width:100%;height:30px;'>
			<span>医生编号：</span>
			<input id='HospitalDoctor-doctor_number' name='doctor_number' value="<?php echo ($HospitalDoctor["doctor_number"]); ?>" />
		</div>
		<div style='width:100%;height:30px;'>
			<span>所属医生：</span>
			<input id='HospitalDoctor-hid' name='hid[]' />
		</div>
		<input type='hidden' name='editHospitalDoctor' value='editHospitalDoctor' />
		<input type='hidden' name='id' value='<?php echo ($HospitalDoctor["id"]); ?>' />
	</form>
</div>
<script type="text/javascript">
$("#HospitalDoctor-title").textbox({
    required: true,
    validType:['length[2,40]'],
    missingMessage: "必须填写医生名称",
    invalidMessage: "已经存在该医生",
});
$("#HospitalDoctor-hid").combotree({
	width: 192,
	checkbox:true,
	multiple:true,
	required: true,
	validType:['length[2,400]'],
	url: '<?php echo U("Oa/Common/treeHospital");?>',
	value: '<?php echo ($HospitalDoctor["hid"]); ?>',
	missingMessage: "必须选择医生所属医院"
});
</script>