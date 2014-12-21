<?php
require("model.function.php");
/**
 * [getUserSession 实时获取用户权限与组信息]
 * @return [type] [description]
 */
function getUserSession(){
	$User = session("user");

	//重组管理员权限
	$AuthIds = array();
	$AuthRules = M("AuthRule")->field('id')->select();
	foreach($AuthRules as $AuthRules_k => $AuthRules_v){
		$AuthIds[] = $AuthRules_v['id'];
	}
	$AuthIds = implode(',', $AuthIds);
	$GroupData = array(
		'id' => 1,
		'rules' => $AuthIds,
	);
	M("AuthGroup")->save($GroupData);

	//初始化Auth权限控制
	$Auth = new \Think\Auth();

	//获取登录用户拥有的用户组相关信息
	$Gids = $Auth->getGroups($User['id']);

    //组合用户拥有的用户组下的所有权限规则
    $Rules = array();
    foreach($Gids as $Gids_k => $Gids_v){
        $ruleIds = explode(",",$Gids_v['rules']);
        foreach($ruleIds as $ruleIds_k => $ruleIds_v){
            $Rules[] = $ruleIds[$ruleIds_k];
        }
    }
    //规则去重复
    $Rules = array_unique($Rules);

    //组合用户拥有的用户组id
    $tmpGids = array();
    foreach($Gids as $Gids_k => $Gids_v){
        $tmpGids[] = $Gids_v['group_id'];
    }
    $Gids = $tmpGids;

	//记录登录用户规则信息
	session("gid",$Gids);
    session("rules",$Rules);

    //获取登录用户系统首页信息
	$user_login_log = M("UserLoginLog")->where(array('uid'=>$User['id']))->order('logindate desc')->find();

    foreach($Gids as $t_k => $t_v){

    	$login_groups = M("AuthGroup")->find($t_v);
    	$tmp_login_info[] = $login_groups['title'];
	}
	$login_info['groups'] = implode(',', $tmp_login_info);
	$login_info['logindate'] = $user_login_log['logindate'];
	$login_info['ip'] = $user_login_log['ip'];
	$login_info['logincity'] = $user_login_log['logincity'];

    session("login_info",$login_info);
}
/**
 * [treeRules 递归获取规则树]
 * @param  [type]  $data [递归循环数据]
 * @param  integer $pid  [父级ID]
 * @return [type]  Array [返回Array树数组]
 */
function treeRules($data,$pid = 0){
	$treeRules = array();
	foreach($data as $data_v){
		if($data_v['pid'] == $pid){
			$data_v['children'] = treeRules($data,$data_v['id']);
			$treeRules[] = $data_v;
		}
	}
	return $treeRules;
}
/**
 * [getTree 获取规则树]
 * @return [type] Array [返回处理的数组]
 */
function getTree(){
	$Rules = M("AuthRule");
	$Rules = $Rules->order("sort asc")->select();
	foreach($Rules as $Rules_k => $Rules_v){
		$Rules[$Rules_k]['text'] = $Rules[$Rules_k]['title'];
		$Rules[$Rules_k]['op'] = $Rules_v['id'];
	}
	$Rules = treeRules($Rules);
	return $Rules;
}
/**
 * [getUsergroup 获取角色组]
 * @return [type] Array [返回处理的数组]
 */
function getUsergroup(){
	$Usergroup = M("AuthGroup");
	$Usergroup = $Usergroup->order("id asc")->select();
	foreach($Usergroup as $Usergroup_k => $Usergroup_v){
		$Usergroup[$Usergroup_k]['text'] = $Usergroup[$Usergroup_k]['title'];
	}
	return $Usergroup;
}
/**
 * [getHospital 获取医院列表]
 * @return [type] Array [返回处理的数组]
 */
function getHospital(){
	$Hospital = M("Hospital");
	$Hospital = $Hospital->order("id asc")->select();
	foreach($Hospital as $Hospital_k => $Hospital_v){
		$Hospital[$Hospital_k]['text'] = $Hospital[$Hospital_k]['title'];
	}
	return $Hospital;
}
/**
 * [doReturn 返回前端ajax处理数据结果]
 * @param  string $success [成功信息]
 * @param  string $error   [失败信息]
 * @param  [type] $status  [处理数据结果]
 * @return [type]          [description]
 */
function doReturn($success = '成功',$error = '失败',$status){
	if($status !== false){
		$msg = array(
			"status" => true,
			"info" => $success
		);
	} else {
		$msg = array(
			"status" => false,
			"info" => $error
		);
	}
	return $msg;
}