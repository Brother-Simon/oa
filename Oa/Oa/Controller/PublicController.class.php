<?php
namespace Oa\Controller;
use Common\Controller\CommonController;

class PublicController extends CommonController{
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
}