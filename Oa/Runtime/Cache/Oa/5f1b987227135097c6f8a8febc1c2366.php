<?php if (!defined('THINK_PATH')) exit();?><div style='word-break:break-all;'>
	<form id='addHospitalDisease' method='post' action="<?php echo U('Oa/System/addHospitalDisease');?>">
		<div style='width:100%;height:30px;'>
			<span>疾病名称：</span>
			<input id='HospitalDisease-title' name='title' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>所属疾病：</span>
			<input id='HospitalDisease-hid' name='hid[]' />
		</div>
		<input type='hidden' name='addHospitalDisease' value='addHospitalDisease' />
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
	missingMessage: "必须选择疾病所属医院",
});
</script>