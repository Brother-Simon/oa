<?php
namespace Common\Controller;
use Think\Controller;
use Think\Auth;

class CommonController extends Controller{
	public function _initialize(){
		//初始化公用变量

		//用户登陆信息检测处理
		$User = session("user");
		if(!$User){
			$this->redirect('Login/index');
		}
		//初始化模板变量
		$AppsWhere = array(
			"pid" => 0,
			"isshow" => 1
		);
		$Apps = M("AuthRule")->where($AppsWhere)->order("sort asc")->select();
		$this->assign("Apps",$Apps);

		//超级管理员免验证
		if($User['id'] == C("Administrator")){
			return true;
		}

		//检查普通用户权限
		$AuthModel = new Auth();
		if(!$AuthModel->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$User['id'])){
			echo "没有权限";
			exit;
		}
	}
}