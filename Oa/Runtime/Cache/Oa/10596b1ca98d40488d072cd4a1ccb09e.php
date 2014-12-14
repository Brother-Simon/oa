<?php if (!defined('THINK_PATH')) exit();?><div style='word-break:break-all;'>
	<form id='editGroup' method='post' action="<?php echo U('Oa/System/editGroup');?>">
		<div style='width:100%;height:30px;'>
			<span>角色名称：</span>
			<input id='Group-title' name='title' value='<?php echo ($Group["title"]); ?>' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>角色状态：</span>
			<input id='status1' name='status' type='radio' value='1' <?php if($Group.status): ?>checked<?php endif; ?> />  <label for='status1'>启用</label>
			<input id='status0' name='status' type='radio' value='0' <?php if($Group["status"] == 0): ?>checked<?php endif; ?> />  <label for='status0'>禁用</label>
		</div>
		<div style='width:100%;height:30px;'>
			<span>授权规则：</span>
			<input id='Group-rules' name='rules[]' />
		</div>
		<input type='hidden' name='editGroup' value='editGroup' />
		<input type='hidden' name='id' value='<?php echo ($Group["id"]); ?>' />
	</form>
</div>
<script type="text/javascript">
$("#Group-title").textbox({
    required: true,
    validType:['length[2,40]','remote[\'/index.php/Oa/System/checkEditGroup/id/<?php echo ($Group["id"]); ?>\',\'title\']'],
    missingMessage: "必须填写角色名称",
    invalidMessage: "已经存在该角色",
});
$("#Group-status").textbox({
	width: 190,
	require: true,
	missingMessage: "必须选择角色状态",
});
$("#Group-rules").combotree({
	width: 192,
	checkbox:true,
	multiple:true,
	value: [<?php echo ($Group["rules"]); ?>],
	url: '/index.php/Oa/System/treeGroup',
	missingMessage: "必须选择角色规则",
	cascadeCheck:false
});
</script>