<?php
namespace Oa\Controller;
use Think\Controller;
class SystemController extends PublicController{
	/**
	 * [rule 规则管理与视图]
	 * @return [type] [description]
	 */
	public function rule(){
		if(IS_POST && I("action") == "getList"){
			$this->ajaxReturn(getTree());
			exit;
		}
		$this->display();
	}
	/**
	 * [sortRule 规则排序方法]
	 * @return [type] [description]
	 */
	public function sortRule(){
		if(IS_POST && I('order')){
			$order = I('order');
			foreach($order as $order_k => $order_v){
				$data = array(
					'id' => $order_k,
					'sort' => $order_v
				);
				M("AuthRule")->save($data);
			}
			$res['status'] = true;
			$res['info'] = '排序更新成功！';
			$this->ajaxReturn($res);
			exit;
		}
	}
	/**
	 * [addRule 规则添加方法]
	 */
	public function addRule(){
		//添加规则方法
		if(IS_POST && I("addRule")){
			$AuthRule = M("AuthRule");
			if(!$AuthRule->create()){
				$msg = array(
					"status" => false,
					"info" => "数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$status = $AuthRule->add();
				if($status){
					$msg = array(
						"status" => true,
						"info" => "规则添加成功"
					);
				} else {
					$msg = array(
						"status" => false,
						"info" => "规则添加失败,未知原因，请联系管理员"
					);
				}
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$pid = I('pid',0,'intval');
		$this->pid = $pid;
		$this->display();
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
	 * [editRule 编辑规则方法与视图]
	 * @return [type] [description]
	 */
	public function editRule(){
		//添加规则方法
		if(IS_POST && I("editRule")){
			$AuthRule = M("AuthRule");
			if(!$AuthRule->create()){
				$msg = array(
					"status" => false,
					"info" => "数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$status = $AuthRule->save();
				if($status !== false){
					$msg = array(
						"status" => true,
						"info" => "规则编辑成功"
					);
				} else {
					$msg = array(
						"status" => false,
						"info" => "规则编辑失败,未知原因，请联系管理员"
					);
				}
			}
			$this->ajaxReturn($msg);
			exit;
		}

		$id = I('id',0,'intval');
		$this->Rule = M("AuthRule")->find($id);
		$this->display();
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
	 * [deleteRule 删除规则方法与视图]
	 * @return [type] [description]
	 */
	public function deleteRule(){
		if(IS_POST && I('id')){
			$status = M("AuthRule")->delete(I('id'));
			if($status !== false){
				$msg = array(
					"status" => true,
					"info" => "规则数据成功删除！"
				);
			} else {
				$msg = array(
					"status" => false,
					"info" => "规则数据成功失败，请联系管理员！"
				);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		echo "真心要删除ID： ".I('id')." 的规则吗？";
	}
	/**
	 * [group 角色列表管理与视图]
	 * @return [type] [description]
	 */
	public function group(){
		if(IS_POST){
			$Groups = M('AuthGroup')->field('id,title,status,rules,id as op')->page(I('page'),I('rows'))->select();
			//遍历循环用户所属组
			foreach($Groups as $k => $v){
				//初始化角色所含用户信息
				$Groups_tmp = array();
				//获取角色所含用户Id集合
				$GroupUserIds = M("AuthGroupAccess")->field("uid")->where(array('group_id'=>$v['id']))->select();
				//获取角色所含用户信息并且组合
				foreach($GroupUserIds as $k2 => $v2){
					$Users = M("User")->field("username")->where(array('id'=>$v2['uid']))->find();
					$Groups_tmp[] = $Users['username'];
				}
				$Groups[$k]['groupuser'] = implode(',',$Groups_tmp);
			}
			$Data = array(
				'total' => M('AuthGroup')->count(),
				'rows' => $Groups
			);
			$this->ajaxReturn($Data);
			exit;
		}
		$this->display();
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
	 * [addGroup 添加角色方法与视图]
	 * @return [type] [description]
	 */
	public function addGroup(){
		if(IS_POST && I("addGroup")){
			$AuthGroup = M("AuthGroup");
			if(!$AuthGroup->create()){
				$msg = array(
					"status" => false,
					"info" => "角色数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$AuthGroup->rules = implode(",",$AuthGroup->rules);
				$status = $AuthGroup->add();
				if($status){
					$msg = array(
						"status" => true,
						"info" => "角色添加成功"
					);
				} else {
					$msg = array(
						"status" => false,
						"info" => "角色添加失败,未知原因，请联系管理员"
					);
				}
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '添加角色';
		$this->display();
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
	 * [checkAddGroup 编辑角色方法与视图]
	 * @return [type] [description]
	 */
	public function editGroup(){
		//编辑角色方法
		if(IS_POST && I("editGroup")){
			$AuthGroup = M("AuthGroup");
			if(!$AuthGroup->create()){
				$msg = array(
					"status" => false,
					"info" => "数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$AuthGroup->rules = implode(",",$AuthGroup->rules);
				$status = $AuthGroup->save();
				if($status !== false){
					$msg = array(
						"status" => true,
						"info" => "角色编辑成功"
					);
				} else {
					$msg = array(
						"status" => false,
						"info" => "角色编辑失败,未知原因，请联系管理员"
					);
				}
			}
			$this->ajaxReturn($msg);
			exit;
		}

		$id = I('id',0,'intval');
		$this->Group = M("AuthGroup")->find($id);
		$this->title = "编辑角色";
		$this->display();
	}
	/**
	 * [deleteGroup 删除角色方法与视图]
	 * @return [type] [description]
	 */
	public function deleteGroup(){
		if(IS_POST && I('id')){
			$status = M("AuthGroup")->delete(I('id'));
			$status2 = M("AuthGroupAccess")->where(array("group_id"=>I('id')))->delete();
			if($status !== false){
				$msg = array(
					"status" => true,
					"info" => "角色数据成功删除！"
				);
			} else {
				$msg = array(
					"status" => false,
					"info" => "角色数据成功失败，请联系管理员！"
				);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		echo "真心要删除ID： ".I('id')." 的角色吗？";
	}
	/**
	 * [user 用户管理与视图]
	 * @return [type] [description]
	 */
	public function user(){
		if(IS_POST){
			$Users = M('User')->field("id,username,name,qq,tel,status,id as op")->page(I('page'),I('rows'))->select();
			//遍历循环用户所属组
			foreach($Users as $k => $v){
				//初始化用户所属组信息
				$Users_tmp = array();
				//获取用户所属组Id集合
				$UserGroupIds = M("AuthGroupAccess")->field("group_id")->where(array('uid'=>$v['id']))->select();
				//获取用户所属组信息并且组合
				foreach($UserGroupIds as $k2 => $v2){
					$Groups = M("AuthGroup")->field("title")->where(array('id'=>$v2['group_id']))->find();
					$Users_tmp[] = $Groups['title'];
				}
				$Users[$k]['usergroup'] = implode(',',$Users_tmp);
				$User_login_log = M("UserLoginLog")->order("id desc")->where(array('uid'=>$Users[$k]['id']))->find();
				$Users[$k]['logindate'] = $User_login_log['logindate'];
				$Users[$k]['loginnums'] = M("UserLoginLog")->where(array('uid'=>$Users[$k]['id']))->count();
			}
			$Data = array(
				'total' => M('User')->count(),
				'rows' => $Users
			);
			$this->ajaxReturn($Data);
			exit;
		}
		$this->title = '用户管理';
		$this->display();
	}
	/**
	 * [addUser 添加用户方法与视图]
	 * @return [type] [description]
	 */
	public function addUser(){
		if(IS_POST && I("addUser")){
			$User = D("User");
			$UserGroup = I("usergroup");
			if(!$User->create()){
				$msg = array(
					"status" => false,
					"info" => "用户数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$status = $User->add();
				if($status){
					//保存用户所属角色
					foreach($UserGroup as $k => $v){
						$data = array(
							'uid' => $status,
							'group_id' => $v
						);
						M("AuthGroupAccess")->add($data);
					}
					$msg = array(
						"status" => true,
						"info" => "用户添加成功"
					);
				} else {
					$msg = array(
						"status" => false,
						"info" => "用户添加失败,未知原因，请联系管理员"
					);
				}
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '添加用户';
		$this->display();
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
	 * [editUser 编辑用户方法与视图]
	 * @return [type] [description]
	 */
	public function editUser(){
		//编辑角色方法
		if(IS_POST && I("editUser")){
			$User = D("User");
			$UserInfo = $User->find(I('id'));
			$UserGroup = I("usergroup");
			if(!$User->create()){
				$msg = array(
					"status" => false,
					"info" => "用户数据创建失败，未知原因，请联系管理员"
				);
			} else {
				//用户密码为空时不修改密码
				if(trim($User->password) == ""){
					$User->password = $UserInfo['password'];
				}
				$status = $User->save();
				if($status !== false){
					//保存用户所属角色
					M("AuthGroupAccess")->where(array("uid"=>$UserInfo['id']))->delete();
					foreach($UserGroup as $k => $v){
						$data = array(
							'uid' => $UserInfo['id'],
							'group_id' => $v
						);
						M("AuthGroupAccess")->add($data);
					}
					$msg = array(
						"status" => true,
						"info" => "用户编辑成功"
					);
				} else {
					$msg = array(
						"status" => false,
						"info" => "用户编辑失败,未知原因，请联系管理员"
					);
				}
			}
			$this->ajaxReturn($msg);
			exit;
		}

		$id = I('id',0,'intval');
		$this->User = M("User")->find($id);
		$UserGroup = M("AuthGroupAccess")->where(array("uid"=>$id))->field("group_id")->select();
		foreach($UserGroup as $k => $v){
			$UserGroup_tmp[] = $UserGroup[$k]['group_id'];
		}
		$this->UserGroup = implode(",",$UserGroup_tmp);
		$this->title = "编辑用户";
		$this->display();
	}
	/**
	 * [deleteUser 删除用户方法与视图]
	 * @return [type] [description]
	 */
	public function deleteUser(){
		if(IS_POST && I('id')){
			$status = M("User")->delete(I('id'));
			$status2 = M("AuthGroupAccess")->where(array("uid"=>I('id')))->delete();
			if($status !== false){
				$msg = array(
					"status" => true,
					"info" => "用户数据成功删除！"
				);
			} else {
				$msg = array(
					"status" => false,
					"info" => "用户数据删除失败，请联系管理员！"
				);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		echo "真心要删除ID： ".I('id')." 的用户吗？";
	}
	/**
	 * [hospital 医院管理与视图]
	 * @return [type] [description]
	 */
	public function hospital(){
		if(IS_POST){
			$Hospital = M('Hospital')->field('id,title,description,status,id as op')->page(I('page'),I('rows'))->select();
			$Data = array(
				'total' => M('Hospital')->count(),
				'rows' => $Hospital
			);
			$this->ajaxReturn($Data);
			exit;
		}
		$this->title = '医院管理';
		$this->display();
	}
	/**
	 * [addHospital 添加医院视图与方法]
	 * @return [type] [description]
	 */
	public function addHospital(){
		if(IS_POST && I("addHospital")){
			$Hospital = M("Hospital");
			if(!$Hospital->create()){
				$msg = array(
					"status" => false,
					"info" => "医院数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$status = $Hospital->add();
				if($status){
					$msg = array(
						"status" => true,
						"info" => "医院添加成功"
					);
				} else {
					$msg = array(
						"status" => false,
						"info" => "医院添加失败,未知原因，请联系管理员"
					);
				}
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '添加医院';
		$this->display();
	}
	/**
	 * [editHospital 编辑医院视图与方法]
	 * @return [type] [description]
	 */
	public function editHospital(){
		if(IS_POST && I("editHospital")){
			$Hospital = M("Hospital");
			if(!$Hospital->create()){
				$msg = array(
					"status" => false,
					"info" => "医院数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$status = $Hospital->save();
				if($status !== false){
					$msg = array(
						"status" => true,
						"info" => "医院编辑成功"
					);
				} else {
					$msg = array(
						"status" => false,
						"info" => "医院编辑失败,未知原因，请联系管理员"
					);
				}
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$id = I('id',0,'intval');
		$this->Hospital = M("Hospital")->find($id);
		$this->title = '编辑医院';
		$this->display();
	}
	/**
	 * [deleteHospital 删除医院视图与方法]
	 * @return [type] [description]
	 */
	public function deleteHospital(){
		if(IS_POST && I('id')){
			$status = M("Hospital")->delete(I('id'));
			if($status !== false){
				$msg = array(
					"status" => true,
					"info" => "医院数据成功删除！"
				);
			} else {
				$msg = array(
					"status" => false,
					"info" => "医院数据删除失败，请联系管理员！"
				);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		echo "真心要删除ID： ".I('id')." 的医院吗？";
	}
}