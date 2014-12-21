<?php
namespace Oa\Controller;
use Think\Controller;

class CommonController extends Controller{
	/**
	 * _initialize 公共函数库
	 * @return [type] [description]
	 */
	public function _initialize(){

		//用户登陆信息检测处理
		$User = session("user");
		if(!$User){
			$this->redirect('Login/index');
		}
	}
	//获取左侧菜单权限列表
	public function getLeftMenu(){
		if(IS_POST){
			$ModuleWhere = array(
				"pid" => I("pid"),
				"isshow" => 1,
				'id' => array('in',implode(',', session('rules'))),
			);
			$Modules = M("AuthRule")->where($ModuleWhere)->order("sort asc")->select();
			foreach($Modules as $Modules_k => $Modules_v){
				$where = array(
					'pid'=> $Modules_v['id'],
					'id' => array('in',implode(',', session('rules'))),
				);
				$Modules_son = M("AuthRule")->order('sort asc')->field("id,title as text,cls as iconCls,name as url")->where($where)->select();
				foreach($Modules_son as $Modules_k2 => $Modules_v2){
					$Modules_son[$Modules_k2]['url'] = "/index.php/".$Modules_v2['url'];
					$Modules_son[$Modules_k2]['type'] = true;
				}
				$Modules[$Modules_k]['children'] = $Modules_son;
			}
			$this->ajaxReturn($Modules);
		}
	}
	/**
	 * [checkAddRule 校验添加规则名称唯一性]
	 * @return [type] [description]
	 */
	public function checkAddRule(){
		//验证是否已存在规则
		if(IS_POST && I('name')){
			$count = M("AuthRule")->where(array("name"=>I("name")))->count();;
			if(!$count){
				echo 'true';
			} else {
				echo 'false';
			}
			exit;
		}
	}
	/**
	 * [treeRule 获取规则下拉树形列表]
	 * @return [type] [description]
	 */
	public function treeRule(){
		if(IS_POST){
			$tree[0] = array(
				'id' => 0,
				'text' => '顶级规则',
				'children' => getTree()
			);
			$this->ajaxReturn($tree);
			exit;
		}
	}
	/**
	 * [checkEditRule 校验编辑规则名称唯一性]
	 * @return [type] [description]
	 */
	public function checkEditRule(){
		//验证是否已存在规则
		if(IS_POST && I('name')){
			$where = array(
				'id' => array('neq',I("get.id")),
				'name' => I("name")
			);
			$AuthRule = M("AuthRule");
			$count = $AuthRule->where($where)->count();;
			if(!$count){
				echo 'true';
			} else {
				echo 'false';
			}
			exit;
		}
	}
	/**
	 * [treeGroup 获取权限下拉节点列表]
	 * @return [type] [description]
	 */
	public function treeGroup(){
		if(IS_POST){
			$this->ajaxReturn(getTree());
			exit;
		}
	}
	/**
	 * [checkAddGroup 校验添加角色名称唯一性]
	 * @return [type] [description]
	 */
	public function checkAddGroup(){
		//验证是否已存在规则
		if(IS_POST && I('title')){
			$count = M("AuthGroup")->where(array("title"=>I("title")))->count();;
			if(!$count){
				echo 'true';
			} else {
				echo 'false';
			}
			exit;
		}
	}
	/**
	 * [checkAddGroup 校验编辑角色名称唯一性]
	 * @return [type] [description]
	 */
	public function checkEditGroup(){
		//验证是否已存在规则
		if(IS_POST && I('title')){
			$where = array(
				'id' => array('neq',I("get.id")),
				'title' => I("title")
			);
			$AuthGroup = M("AuthGroup");
			$count = $AuthGroup->where($where)->count();;
			if(!$count){
				echo 'true';
			} else {
				echo 'false';
			}
			exit;
		}
	}
	/**
	 * [checkAddUser 校验添加用户名称唯一性]
	 * @return [type] [description]
	 */
	public function checkAddUser(){
		//验证是否已存在
		if(IS_POST && I('username')){
			$count = M("User")->where(array("username"=>I("username")))->count();;
			if(!$count){
				echo 'true';
			} else {
				echo 'false';
			}
			exit;
		}
	}
	/**
	 * [checkEditUser 校验编辑用户名称唯一性]
	 * @return [type] [description]
	 */
	public function checkEditUser(){
		//验证是否已存在
		if(IS_POST && I('username')){
			$where = array(
				'id' => array('neq',I("get.id")),
				'username' => I("username")
			);
			$User = M("User");
			$count = $User->where($where)->count();;
			if(!$count){
				echo 'true';
			} else {
				echo 'false';
			}
			exit;
		}
	}
	/**
	 * [treeUsergroup 获取角色列表]
	 * @return [type] [description]
	 */
	public function treeUsergroup(){
		if(IS_POST){
			$this->ajaxReturn(getUsergroup());
			exit;
		}
	}
	/**
	 * treeHospital 获取医院列表
	 * @return [type] [description]
	 */
	public function treeHospital(){
		if(IS_POST){
			$this->ajaxReturn(getHospital());
			exit;
		}
	}

}