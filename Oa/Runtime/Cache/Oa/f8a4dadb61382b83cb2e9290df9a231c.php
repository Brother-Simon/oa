<?php if (!defined('THINK_PATH')) exit();?><div style='word-break:break-all;'>
	<form id='addRule' method='post' action="<?php echo U('Oa/System/addRule');?>">
		<div style='width:100%;height:30px;'>
			<span>上级规则：</span>
			<input id='Rule-pid' name='pid'>
		</div>
		<div style='width:100%;height:30px;'>
			<span>规则名称：</span>
			<input id='Rule-title' name='title' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>规则标识：</span>
			<input id='Rule-name' name='name' style='width:188px;' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>规则类型：</span>
			<input id='type1' name='type' type='radio' value='1' checked />  <label for='type1'>URL模式</label>
			<input id='type2' name='type' type='radio' value='2' />  <label for='type2'>菜单模式</label>
		</div>
		<div style='width:100%;height:30px;'>
			<span>规则状态：</span>
			<input id='status1' name='status' type='radio' value='1' checked />  <label for='status1'>启用</label>
			<input id='status0' name='status' type='radio' value='0' />  <label for='status0'>禁用</label>
		</div>
		<div style='width:100%;height:30px;'>
			<span>规则条件：</span>
			<input id='Rule-condition' name='condition' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>规则排序：</span>
			<input id='Rule-sort' name='sort' value='50' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>规则显示：</span>
			<input id='isshow1' name='isshow' type='radio' value='1' checked />  <label for='isshow1'>显示</label>
			<input id='isshow0' name='isshow' type='radio' value='0' />  <label for='isshow0'>隐藏</label>
		</div>
		<div style='width:100%;height:30px;'>
			<span>规则图标：</span>
			<input id='Rule-cls' name='cls' />
		</div>
		<input type='hidden' name='addRule' value='addRule' />
	</form>
</div>
<script type="text/javascript">
$.extend($.fn.textbox.defaults.rules, {
	Rule: {
		validator: function(value){
			return /([A-Z][a-z1-9])+/.test(value);
		},
		message: '必须为首字母大写的驼峰法命名'
	},
});
$("#Rule-pid").combotree({
	width: 192,
	value: <?php echo ($pid); ?>,
	url: '<?php echo U("Oa/Common/treeRule");?>',
	require: true,
});
$("#Rule-title").textbox({
	width: 190,
	require: true,
	missingMessage: "必须填写规则名称",
});
$("#Rule-name").textbox({
    required: true,
    validType:['length[2,40]','remote[\'<?php echo U("Oa/Common/checkAddRule");?>\',\'name\']'],
    missingMessage: "必须填写规则URL",
    invalidMessage: "已经存在该规则",
});
$("#Rule-condition").textbox({
	width: 190,
});
$("#Rule-sort").textbox({
	width: 50,
	require: true,
	missingMessage: "必须填写排序值",
	prompt: 50,
});
$("#Rule-cls").combotree({
	width: 190,
	value: 'tree-file',
	url: "/Public/Json/icon.php",
});
</script>