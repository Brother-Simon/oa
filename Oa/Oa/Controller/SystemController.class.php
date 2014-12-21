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
				$msg = doReturn("规则添加成功","规则添加失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$pid = I('pid',0,'intval');
		$this->pid = $pid;
		$this->display();
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
				$msg = doReturn("规则编辑成功","规则编辑失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}

		$id = I('id',0,'intval');
		$this->Rule = M("AuthRule")->find($id);
		$this->display();
	}
	/**
	 * [deleteRule 删除规则方法与视图]
	 * @return [type] [description]
	 */
	public function deleteRule(){
		if(IS_POST && I('id')){
			$status = M("AuthRule")->delete(I('id'));
			$msg = doReturn("规则数据成功删除","规则数据成功失败，请联系管理员",$status);
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
				$msg = doReturn("角色添加成功","角色添加失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '添加角色';
		$this->display();
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
				$msg = doReturn("角色编辑成功","角色编辑失败,未知原因，请联系管理员",$status);
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
			//同步删除组用户表权限节点信息
			M("AuthGroupAccess")->where(array("group_id"=>I('id')))->delete();
			$msg = doReturn("角色数据成功删除","角色数据成功失败，请联系管理员",$status);
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
			//同步删除组用户权限表信息
			M("AuthGroupAccess")->where(array("uid"=>I('id')))->delete();
			$msg = doReturn("用户数据成功删除","用户数据删除失败，请联系管理员",$status);
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
				$msg = doReturn("医院添加成功","医院添加失败,未知原因，请联系管理员",$status);
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
				$msg = doReturn("医院编辑成功","医院编辑失败,未知原因，请联系管理员",$status);
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
			$msg = doReturn("医院数据成功删除","医院数据删除失败，请联系管理员",$status);
			$this->ajaxReturn($msg);
			exit;
		}
		echo "真心要删除ID： ".I('id')." 的医院吗？";
	}
	/**
	 * [hospitalDoctor 医生管理视图与方法]
	 * @return [type] [description]
	 */
	public function hospitalDoctor(){
		if(IS_POST){
			$Hospital = M('HospitalDoctor')->field('id,title,doctor_number,hid,id as op')->page(I('page'),I('rows'))->select();
			foreach($Hospital as $k => $v){
				$tmp_hospital_title = array();
				$tmp_hid = explode(',', $v['hid']);
				foreach($tmp_hid as $k2 => $v2){
					$tmp_hospital = M("Hospital")->find($v2);
					$tmp_hospital_title[] = $tmp_hospital['title'];
				}
				$Hospital[$k]['hospital'] = implode(',', $tmp_hospital_title);
			}
			$Data = array(
				'total' => M('Hospital')->count(),
				'rows' => $Hospital
			);
			$this->ajaxReturn($Data);
			exit;
		}
		$this->title = "医生管理";
		$this->display();
	}
	/**
	 * [addHospitalDoctor 添加医生视图与方法]
	 * @return [type] [description]
	 */
	public function addHospitalDoctor(){
		if(IS_POST && I("addHospitalDoctor")){
			$HospitalDoctor = M("HospitalDoctor");
			if(!$HospitalDoctor->create()){
				$msg = array(
					"status" => false,
					"info" => "医生数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$HospitalDoctor->hid = implode(',', $HospitalDoctor->hid);
				$status = $HospitalDoctor->add();
				$msg = doReturn("医生添加成功","医生添加失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '添加医生';
		$this->display();
	}
	/**
	 * [editHospitalDoctor 编辑医生视图与方法]
	 * @return [type] [description]
	 */
	public function editHospitalDoctor(){
		if(IS_POST && I("editHospitalDoctor")){
			$HospitalDoctor = M("HospitalDoctor");
			if(!$HospitalDoctor->create()){
				$msg = array(
					"status" => false,
					"info" => "医生数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$HospitalDoctor->hid = implode(',', $HospitalDoctor->hid);
				$status = $HospitalDoctor->save();
				$msg = doReturn("医生编辑成功","医生编辑失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '编辑医生';
		$id = I('id',0,'intval');
		$this->HospitalDoctor = M("HospitalDoctor")->find($id);
		$this->display();
	}
	/**
	 * [deleteHospitalDoctor 删除医生视图与方法]
	 * @return [type] [description]
	 */
	public function deleteHospitalDoctor(){
		if(IS_POST && I('id')){
			$status = M("HospitalDoctor")->delete(I('id'));
			$msg = doReturn("医生数据成功删除","医生数据删除失败，请联系管理员",$status);
			$this->ajaxReturn($msg);
			exit;
		}
		echo "真心要删除ID： ".I('id')." 的医生吗？";
	}
	/**
	 * [hospitalDepartment 科室管理视图与方法]
	 * @return [type] [description]
	 */
	public function hospitalDepartment(){
		if(IS_POST){
			$Hospital = M('HospitalDepartment')->field('id,title,hid,id as op')->page(I('page'),I('rows'))->select();
			foreach($Hospital as $k => $v){
				$tmp_hospital_title = array();
				$tmp_hid = explode(',', $v['hid']);
				foreach($tmp_hid as $k2 => $v2){
					$tmp_hospital = M("Hospital")->find($v2);
					$tmp_hospital_title[] = $tmp_hospital['title'];
				}
				$Hospital[$k]['hospital'] = implode(',', $tmp_hospital_title);
			}
			$Data = array(
				'total' => M('Hospital')->count(),
				'rows' => $Hospital
			);
			$this->ajaxReturn($Data);
			exit;
		}
		$this->title = "科室管理";
		$this->display();
	}
	/**
	 * [addHospitalDepartment 添加科室视图与方法]
	 * @return [type] [description]
	 */
	public function addHospitalDepartment(){
		if(IS_POST && I("addHospitalDepartment")){
			$HospitalDepartment = M("HospitalDepartment");
			if(!$HospitalDepartment->create()){
				$msg = array(
					"status" => false,
					"info" => "科室数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$HospitalDepartment->hid = implode(',', $HospitalDepartment->hid);
				$status = $HospitalDepartment->add();
				$msg = doReturn("科室添加成功","科室添加失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '添加科室';
		$this->display();
	}
	/**
	 * [editHospitalDepartment 编辑科室视图与方法]
	 * @return [type] [description]
	 */
	public function editHospitalDepartment(){
		if(IS_POST && I("editHospitalDepartment")){
			$HospitalDepartment = M("HospitalDepartment");
			if(!$HospitalDepartment->create()){
				$msg = array(
					"status" => false,
					"info" => "科室数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$HospitalDepartment->hid = implode(',', $HospitalDepartment->hid);
				$status = $HospitalDepartment->save();
				$msg = doReturn("科室编辑成功","科室编辑失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '编辑科室';
		$id = I('id',0,'intval');
		$this->HospitalDepartment = M("HospitalDepartment")->find($id);
		$this->display();
	}
	/**
	 * [deleteHospitalDepartment 删除科室视图与方法]
	 * @return [type] [description]
	 */
	public function deleteHospitalDepartment(){
		if(IS_POST && I('id')){
			$status = M("HospitalDepartment")->delete(I('id'));
			$msg = doReturn("科室数据成功删除","科室数据删除失败，请联系管理员",$status);
			$this->ajaxReturn($msg);
			exit;
		}
		echo "真心要删除ID： ".I('id')." 的科室吗？";
	}
	/**
	 * [hospitalDisease 疾病管理视图与方法]
	 * @return [type] [description]
	 */
	public function hospitalDisease(){
		if(IS_POST){
			$Hospital = M('HospitalDisease')->field('id,title,hid,id as op')->page(I('page'),I('rows'))->select();
			foreach($Hospital as $k => $v){
				$tmp_hospital_title = array();
				$tmp_hid = explode(',', $v['hid']);
				foreach($tmp_hid as $k2 => $v2){
					$tmp_hospital = M("Hospital")->find($v2);
					$tmp_hospital_title[] = $tmp_hospital['title'];
				}
				$Hospital[$k]['hospital'] = implode(',', $tmp_hospital_title);
			}
			$Data = array(
				'total' => M('Hospital')->count(),
				'rows' => $Hospital
			);
			$this->ajaxReturn($Data);
			exit;
		}
		$this->title = "疾病管理";
		$this->display();
	}
	/**
	 * [addHospitalDisease 添加疾病视图与方法]
	 * @return [type] [description]
	 */
	public function addHospitalDisease(){
		if(IS_POST && I("addHospitalDisease")){
			$HospitalDisease = M("HospitalDisease");
			if(!$HospitalDisease->create()){
				$msg = array(
					"status" => false,
					"info" => "疾病数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$HospitalDisease->hid = implode(',', $HospitalDisease->hid);
				$status = $HospitalDisease->add();
				$msg = doReturn("疾病添加成功","疾病添加失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '添加疾病';
		$this->display();
	}
	/**
	 * [editHospitalDisease 编辑疾病视图与方法]
	 * @return [type] [description]
	 */
	public function editHospitalDisease(){
		if(IS_POST && I("editHospitalDisease")){
			$HospitalDisease = M("HospitalDisease");
			if(!$HospitalDisease->create()){
				$msg = array(
					"status" => false,
					"info" => "疾病数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$HospitalDisease->hid = implode(',', $HospitalDisease->hid);
				$status = $HospitalDisease->save();
				$msg = doReturn("疾病编辑成功","疾病编辑失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '编辑疾病';
		$id = I('id',0,'intval');
		$this->HospitalDisease = M("HospitalDisease")->find($id);
		$this->display();
	}
	/**
	 * [deleteHospitalDisease 删除疾病视图与方法]
	 * @return [type] [description]
	 */
	public function deleteHospitalDisease(){
		if(IS_POST && I('id')){
			$status = M("HospitalDisease")->delete(I('id'));
			$msg = doReturn("疾病数据成功删除","疾病数据删除失败，请联系管理员",$status);
			$this->ajaxReturn($msg);
			exit;
		}
		echo "真心要删除ID： ".I('id')." 的疾病吗？";
	}
	/**
	 * [hospitalSite 站点管理视图与方法]
	 * @return [type] [description]
	 */
	public function hospitalSite(){
		if(IS_POST){
			$Hospital = M('HospitalSite')->field('id,title,url,hid,id as op')->page(I('page'),I('rows'))->select();
			foreach($Hospital as $k => $v){
				$tmp_hospital_title = array();
				$tmp_hid = explode(',', $v['hid']);
				foreach($tmp_hid as $k2 => $v2){
					$tmp_hospital = M("Hospital")->find($v2);
					$tmp_hospital_title[] = $tmp_hospital['title'];
				}
				$Hospital[$k]['hospital'] = implode(',', $tmp_hospital_title);
			}
			$Data = array(
				'total' => M('Hospital')->count(),
				'rows' => $Hospital
			);
			$this->ajaxReturn($Data);
			exit;
		}
		$this->title = "站点管理";
		$this->display();
	}
	/**
	 * [addHospitalSite 添加站点视图与方法]
	 * @return [type] [description]
	 */
	public function addHospitalSite(){
		if(IS_POST && I("addHospitalSite")){
			$HospitalSite = M("HospitalSite");
			if(!$HospitalSite->create()){
				$msg = array(
					"status" => false,
					"info" => "站点数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$HospitalSite->hid = implode(',', $HospitalSite->hid);
				$status = $HospitalSite->add();
				$msg = doReturn("站点添加成功","站点添加失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '添加站点';
		$this->display();
	}
	/**
	 * [editHospitalSite 编辑站点视图与方法]
	 * @return [type] [description]
	 */
	public function editHospitalSite(){
		if(IS_POST && I("editHospitalSite")){
			$HospitalSite = M("HospitalSite");
			if(!$HospitalSite->create()){
				$msg = array(
					"status" => false,
					"info" => "站点数据创建失败，未知原因，请联系管理员"
				);
			} else {
				$HospitalSite->hid = implode(',', $HospitalSite->hid);
				$status = $HospitalSite->save();
				$msg = doReturn("站点编辑成功","站点编辑失败,未知原因，请联系管理员",$status);
			}
			$this->ajaxReturn($msg);
			exit;
		}
		$this->title = '编辑站点';
		$id = I('id',0,'intval');
		$this->HospitalSite = M("HospitalSite")->find($id);
		$this->display();
	}
	/**
	 * [deleteHospitalSite 删除站点视图与方法]
	 * @return [type] [description]
	 */
	public function deleteHospitalSite(){
		if(IS_POST && I('id')){
			$status = M("HospitalSite")->delete(I('id'));
			$msg = doReturn("站点数据成功删除","站点数据删除失败，请联系管理员",$status);
			$this->ajaxReturn($msg);
			exit;
		}
		echo "真心要删除ID： ".I('id')." 的站点吗？";
	}

}