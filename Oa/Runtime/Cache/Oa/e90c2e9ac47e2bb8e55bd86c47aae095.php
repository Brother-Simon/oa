<?php if (!defined('THINK_PATH')) exit();?><div style='padding:10px;word-break:break-all;'>
	<form id='addUser' method='post' action="<?php echo U('Oa/System/addUser');?>">
		<div style='width:100%;height:30px;'>
			<span>登陆名称：</span>
			<input id='User-username' name='username' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>用户姓名：</span>
			<input id='User-name' name='name' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>登陆密码：</span>
			<input type='password' id='User-password' name='password' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>确认密码：</span>
			<input type='password' id='User-password2' name='password2' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>&nbsp;&nbsp;QQ号码：</span>
			<input id='User-qq' name='qq' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>联系电话：</span>
			<input id='User-tel' name='tel' />
		</div>
		<div style='width:100%;height:30px;'>
			<span>用户状态：</span>
			<input id='status1' name='status' type='radio' value='1' checked />  <label for='status1'>启用</label>
			<input id='status0' name='status' type='radio' value='0' />  <label for='status0'>禁用</label>
		</div>
		<div style='width:100%;height:30px;'>
			<span>所属角色：</span>
			<input id='User-usergroup' name='usergroup[]' />
		</div>
		<input type='hidden' name='addUser' value='addUser' />
	</form>
</div>
<script type="text/javascript">
$.extend($.fn.validatebox.defaults.rules, {
	/*必须和某个字段相等*/
	eqPass: {
		validator: function (value, param) {
			return $(param[0]).val() == value;
		},
		message: '两次密码输入不一致'
	}
});
$("#User-username").textbox({
    required: true,
    validType:['length[2,40]','remote[\'/index.php/Oa/System/checkAddUser\',\'username\']'],
    missingMessage: "必须填写用户名称",
    invalidMessage: "已经存在该用户",
});
$("#User-name").textbox({
	required: true,
	validType:['length[2,10]'],
	missingMessage: "用户姓名不能为空",
});
$("#User-password").textbox({
	required: true,
	validType:['length[6,15]'],
	missingMessage: "登陆密码不能为空",
});
$("#User-password2").textbox({
	required: true,
	validType:['eqPass["#User-password"]'],
	missingMessage: "请再次输入登陆密码",
});
$("#User-qq").textbox({
	required: true,
	validType:['length[3,15]'],
	missingMessage: "QQ号码不能为空",
});
$("#User-tel").textbox({
	required: true,
	validType:['length[6,11]'],
	missingMessage: "联系电话不能为空",
});
$("#User-usergroup").combotree({
	width: 192,
	checkbox:true,
	multiple:true,
	required: true,
	validType:['length[2,400]'],
	url: '/index.php/Oa/System/treeUsergroup',
	missingMessage: "必须选择用户所属组",
});
</script>