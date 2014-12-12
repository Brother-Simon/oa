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
		$ip = new Org\Net\IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
		$location = $ip->getlocation();
		$location['area'] = $location['area'] == "" ? "本地服务器" : $location['area'];

		$data['uid'] = $User['id'];
		$data['ip'] = $location['ip'];
		$data['logindate'] = date("Y-m-d H:i:s",time());
		$data['logincity'] = $location['area'];
		M('UserLoginLog')->data($data)->add();
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