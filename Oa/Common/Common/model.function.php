<?php
// 校验验证码	@return boolean
function checkVerify(){
	$verify = new \Think\Verify();
	return $verify->check(I('verify'));
}
// 校验用户名是否存在	@return boolean
function checkUsername(){
	$User = M("User")->where(array("username"=>I("username")))->find();
	if($User<=0){
		return false;
	}
}
// 校验密码是否正确是否存在	@return boolean
function checkPassword(){
	$User = M("User")->where(array("username"=>I("username"),"password"=>I("password",0,'md5')))->find();
	//密码错误返回false
	if($User<=0){
		return false;
	} else {
		//用户登录信息处理
		$data['id'] = $User['id'];
		$data['logindate'] = time();
		$data['loginnums'] = $User['loginnums']+1;
		M('User')->save($data);

		//用户登录成功数据处理函数
		loginSuccess($User);
	}
}
//用户登录成功处理函数
function loginSuccess($User){

	//记录登陆用户session信息
	session("user",$User);
	//获取用户权限与组信息
	getUserSession();
}