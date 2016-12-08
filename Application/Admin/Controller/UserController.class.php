<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class UserController extends CommonController {
	function _filter(&$map){
		$map['id'] = array('egt',2);
		$map['account'] = array('like',"%".I('account')."%");
	}
	
	public function _before_add(){
		$list = D('Site')->where('state=1')->getField('id,title');
		$this->assign('list',$list);
	}
	
	public function _before_edit(){
		$list = D('Site')->where('state=1')->getField('id,title');
		$this->assign('list',$list);
	}
	
	// 检查帐号
	public function checkAccount() {
		if(!preg_match('/^[a-z]\w{4,}$/i',$_POST['account'])) {
			$this->ajaxerror('用户名必须是字母，且5位以上！');
		}
		$User = M("User");
		// 检测用户名是否冲突
		$name['account']  = array('like',I('account'));
		$result  =  $User->where($name)->getField('account');
		if (empty($result)) {
			//成功提示
			$this->ajaxsuccess('该用户名可以使用！');
		} else {
			//失败提示
			$this->ajaxerror('该用户名已经存在！');
		}
		
	}
	public function psd(){
	
		$this->display();
	
	}
	
	//重置密码
	public function resetPwd()
	{
		$id  =  $_POST['id'];
		$password = $_POST['password'];
		if(''== trim($password)) {
			$this->error('密码不能为空！');
		}
		$User = M('User');
		$User->password	=	md5($password);
		$User->id			=	$id;
		$result	=	$User->save();
		if(false !== $result) {
			$this->ajaxsuccess("密码修改为$password");
		}else {
			$this->ajaxerror('密码修改失败！');
		}
	}
	
	public function forbid(){
		$this->gforbid('status');
	}
	public function resume(){
		$this->gresume('status');
	}
}