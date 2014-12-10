<?php
namespace Oa\Controller;
use Think\Controller;
class IndexController extends PublicController {
    public function index(){
		//初始化变量
		$this->page_title = '系统中心_'.C('TITLE');
        $this->display();
    }
}