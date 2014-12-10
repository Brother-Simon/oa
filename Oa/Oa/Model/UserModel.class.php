<?php
namespace Oa\Model;
use Think\Model;
class UserModel extends Model{
   	protected $_validate = array(
        array('verify','checkVerify','验证码不正确',0,'function'),  // 存在字段就验证
        array('username','checkUsername','用户名不正确',1,'function',4),	//登录时验证账号
        array('password','checkPassword','密码不正确',1,'function',4),	//登录时验证账号
   	);
   	protected $_auto = array (
		array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
		array('createdate','time',1,'function') , // 对password字段在新增和编辑的时候使md5函数处理
	);
}