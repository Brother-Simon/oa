<?php if (!defined('THINK_PATH')) exit();?><div style='word-break:break-all;'>
	<form id='editHospitalDisease' method='post' action="<?php echo U('Oa/System/editHospitalDisease');?>">
		<div style='width:100%;height:30px;'>
			<span>疾病名称：</span>
			<input id='HospitalDisease-title' name='title' value="<?php echo ($HospitalDisease["title"]); ?>" />
		</div>
		<div style='width:100%;height:30px;'>
			<span>所属疾病：</span>
			<input id='HospitalDisease-hid' name='hid[]' />
		</div>
		<input type='hidden' name='editHospitalDisease' value='editHospitalDisease' />
		<input type='hidden' name='id' value='<?php echo ($HospitalDisease["id"]); ?>' />
	</form>
</div>
<script type="text/javascript">
$("#HospitalDisease-title").textbox({
    required: true,
    validType:['length[2,40]'],
    missingMessage: "必须填写疾病名称",
    invalidMessage: "已经存在该疾病",
});
$("#HospitalDisease-hid").combotree({
	width: 192,
	checkbox:true,
	multiple:true,
	required: true,
	validType:['length[2,400]'],
	url: '<?php echo U("Oa/Common/treeHospital");?>',
	value: '<?php echo ($HospitalDisease["hid"]); ?>',
	missingMessage: "必须选择疾病所属医院"
});
</script>