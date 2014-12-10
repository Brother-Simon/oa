<?php
namespace Oa\Controller;
use Think\Controller;

class LoginController extends Controller{
	public function index(){
		if(IS_POST){
			$User = D("User");
			if(!$User->create($_POST,4)){//登陆失败
				$msg['info'] = $User->getError();
				$msg['status'] = "error";
				$this->ajaxReturn($msg);
			} else {
				$msg['info'] = "成功登陆，正在跳转会员中心！";
				$msg['status'] = "success";
				$msg['url'] = U("Index/index");
				$this->ajaxReturn($msg);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		//初始化变量
		$this->page_title = '用户登录_'.C('TITLE');
		$this->display();
	}
	public function verify(){
		$config =    array(
			'fontSize'    =>    20,    // 验证码字体大小
		    'length'      =>    3,     // 验证码位数
		    'imageH'	  => 	35,
		    'useNoise'    =>    false, // 关闭验证码杂点
		);
		$verify = new \Think\Verify($config);
		$verify->codeSet = '0123456789';
		$verify->entry();
	}
	public function logout(){
		session(null);
		$this->success("成功退出，正在转向登陆页面！",U('Login/index'));
	}
}