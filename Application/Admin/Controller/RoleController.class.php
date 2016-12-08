<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
/**
 +----------------------------------------------------------
 * 创建人：mike
 * 创建时间：2016-11-27
 * 修改时间：
 +----------------------------------------------------------
 * 控制器说明：权限群组-控制器
 +----------------------------------------------------------
 * 继承：公共控制器
 +----------------------------------------------------------
 * 备注：
 +----------------------------------------------------------
 */
class RoleController extends CommonController {
	
	
	
	public function _filter(&$map)
	{
		$map['name'] = array('like',I('search').'%');   
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：编辑之前  获取分类列表
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	public function _before_edit(){
		$Group = D('Role');
		//查找满足条件的列表数据
		$list     = $Group->field('id,name')->select();
		$this->assign('list',$list);
	
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：添加之前  获取分类列表
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	public function _before_add(){
		$Group = D('Role');
		//查找满足条件的列表数据
		$list     = $Group->field('id,name')->select();
		$this->assign('list',$list);
	
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：权限列表
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	public function power(){
		$map['id'] = I("groupId");
		$name = M("Role")->where($map)->getField('name');
		$this->assign('rolename',$name);
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：设置应用权限
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	public function setApp()
	{
		$id     = $_POST['groupAppId'];
		$groupId	=	$_POST['groupId'];
		$group    =   D('Role');
		$group->delGroupApp($groupId);
		$result = $group->setGroupApps($groupId,$id);
	
	    if($result===false) {
			$data['status']  = 0;
			$data['content'] = '模块授权失败';
			$this->ajaxReturn($data,'json');
		}else {
			$data['status']  = 1;
			$data['content'] = '模块授权成功';
			$this->ajaxReturn($data,'json');
		}
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：权限列表
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	public function app(){
		//读取系统的模块（分组）列表
		$node    =  D("Node");
		$appList	=	$node->where('level=1')->getField('id,title');
																		//dump($appList);
		//读取用户组列表
		$group   =  D('Role');
		$groupList =  $group->getField('id,name');
		
		$this->assign("groupList",$groupList);
		
		//获取当前   用户组    项目权限信息
		$groupId =  isset($_GET['groupId'])?$_GET['groupId']:'';
		$groupAppList = array();
		if(!empty($groupId)) {
			$this->assign("selectGroupId",$groupId);
			//获取当前组的操作权限列表
			$list	=	$group->getGroupAppList($groupId);
			foreach ($list as $vo){
				$groupAppList[$vo['id']]	=	$vo['id'];
			}
		}
		//$this->assign('groupAppList',$groupAppList);
		//$this->assign('appList',$appList);
		
		foreach ($appList as $k=>$vo){
			if (in_array($k, $groupAppList)){
				$xuanzhong[$k]=$vo;
			}else {
				$meixuanzhong[$k]=$vo;
			}
		}
		
		$this->assign('xz',$xuanzhong);
		$this->assign('mxz',$meixuanzhong);
		
		$this->display();
		
		return;
	
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：模块授权 列表
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	public function module()
	{
		$groupId =  I('groupId');
		$appId  = I('appId'); //节点表的  顶级模块
	
		$group   =  D("Role");
		$xuanzhong = array();
		$meixuanzhong = array();
		//读取系统组列表
		$groupList=$group->getField('id,name');
		$this->assign("groupList",$groupList);
	
		if(!empty($groupId)) {
			$this->assign("selectGroupId",$groupId);
			//读取系统组的授权项目列表
			$list	=	$group->getGroupAppList($groupId);
			foreach ($list as $vo){
				$appList[$vo['id']]	=	$vo['title'];
			}
			$this->assign("appList",$appList);
		}
		$node    =  D("Node");
		if(!empty($appId)) {
			$this->assign("selectAppId",$appId);
			//读取当前项目的模块列表
			$where['level']=2;
			$where['pid']=$appId;
			$moduleList=$node->where($where)->getField('id,title');
		}
	
		//获取当前项目的授权模块信息
		$groupModuleList = array();
		if(!empty($groupId) && !empty($appId)) {
			$grouplist	=	$group->getGroupModuleList($groupId,$appId);
			foreach ($grouplist as $vo){
				$groupModuleList[$vo['id']]	=	$vo['id'];
			}
		}
	
		
		foreach ($moduleList as $k=>$vo){
			if (in_array($k, $groupModuleList)){
				$xuanzhong[$k]=$vo;
			}else {
				$meixuanzhong[$k]=$vo;
			}
		}
		
		$this->assign('xz',$xuanzhong);
		$this->assign('mxz',$meixuanzhong);
	
		$this->display();
	    // dump($groupModuleList);
	   //  dump($moduleList);
	    // dump($appList);
		return;
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-13
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：设置模块操作权限 【应用-模块-方法】
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	public function setModule()
	{
		$id     = $_POST['groupModuleId'];
		$groupId	=	$_POST['groupId'];
		$appId	=	$_POST['appId'];
		$group    =   D("Role");
		$group->delGroupModule($groupId,$appId);
		$result = $group->setGroupModules($groupId,$id);
	
		if($result===false) {
			$data['status']  = 0;
			$data['content'] = '模块授权失败';
			$this->ajaxReturn($data,'json');
		}else {
			$data['status']  = 1;
			$data['content'] = '模块授权成功';
			$this->ajaxReturn($data,'json');
		}
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-13
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：设置方法操作权限 【应用-模块-方法】
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	public function setAction()
	{
		$id     = $_POST['groupActionId'];
		$groupId	=	$_POST['groupId'];
		$moduleId	=	$_POST['moduleId'];
		$group    =   D("Role");
		$group->delGroupAction($groupId,$moduleId);
		$result = $group->setGroupActions($groupId,$id);
	
		 if($result===false) {
			$data['status']  = 0;
			$data['content'] = '模块授权失败';
			$this->ajaxReturn($data,'json');
		}else {
			$data['status']  = 1;
			$data['content'] = '模块授权成功';
			$this->ajaxReturn($data,'json');
		}
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-13
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：获取方法列表【应用-模块-方法】
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	public function action()
	{
		$groupId =  I('groupId');
		$appId  = I('appId');
		$moduleId  = I('moduleId');
	
		$group   =  D("Role");
		//读取系统组列表
		$grouplist=$group->field('id,name')->select();
		foreach ($grouplist as $vo){
			$groupList[$vo['id']]	=	$vo['name'];
		}
		$this->assign("groupList",$groupList);
	
		if(!empty($groupId)) {
			$this->assign("selectGroupId",$groupId);
			//读取系统组的授权项目列表
			$list	=	$group->getGroupAppList($groupId);
			foreach ($list as $vo){
				$appList[$vo['id']]	=	$vo['title'];
			}
			$this->assign("appList",$appList);
		}
		if(!empty($appId)) {
			$this->assign("selectAppId",$appId);
			//读取当前项目的授权模块列表
			$list	=	$group->getGroupModuleList($groupId,$appId);
			foreach ($list as $vo){
				$moduleList[$vo['id']]	=	$vo['title'];
			}
			$this->assign("moduleList",$moduleList);
		}
		$node    =  D("Node");
	
		if(!empty($moduleId)) {
			$this->assign("selectModuleId",$moduleId);
			//读取当前项目的操作列表
			$map['level']=3;
			$map['pid']=$moduleId;
			$list	=	$node->where($map)->field('id,title')->select();
			if($list) {
				foreach ($list as $vo){
					$actionList[$vo['id']]	=	$vo['title'];
				}
			}
		}
	
	
		//获取当前用户组操作权限信息
		$groupActionList = array();
		if(!empty($groupId) && !empty($moduleId)) {
			//获取当前组的操作权限列表
			$list	=	$group->getGroupActionList($groupId,$moduleId);
			if($list) {
				foreach ($list as $vo){
					$groupActionList[$vo['id']]	=	$vo['id'];
				}
			}
	
		}
		
		foreach ($actionList as $k=>$vo){
			if (in_array($k, $groupActionList)){
				$xuanzhong[$k]=$vo;
			}else {
				$meixuanzhong[$k]=$vo;
			}
		}
		
		$this->assign('xz',$xuanzhong);
		$this->assign('mxz',$meixuanzhong);
		
		$this->display();
	
		return;
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-13
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：获取群组对应的用户【应用-模块-方法】
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	public function user()
	{
		
		$group    =   D("Role");
		$list=$group->field('id,name')->select();
		foreach ($list as $vo){
			$groupList[$vo['id']]	=	$vo['name'];
		}
		$this->assign("groupList",$groupList);
	
		//获取当前用户组信息
		$groupId = I('groupId');
		$groupId = !empty($groupId)? $groupId:'';
		
		//读取系统的用户列表
		if($groupId<>0) {
			$user    =   D("User");
			$list2=$user->field('id,account,nickname')->select();
			foreach ($list2 as $vo){
				$userList[$vo['id']]	=	$vo['account'].' '.$vo['nickname'];
			}
		}
		
		$groupUserList = array();
		if(!empty($groupId)) {
			$this->assign("selectGroupId",$groupId);
			//获取当前组的用户列表
			$list	=	$group->getGroupUserList($groupId);
			foreach ($list as $vo){
				$groupUserList[$vo['id']]	=	$vo['id'];
			}
	
		}
	
		
		foreach ($userList as $k=>$vo){
			if (in_array($k, $groupUserList)){
				$xuanzhong[$k]=$vo;
			}else {
				$meixuanzhong[$k]=$vo;
			}
		}
		
		$this->assign('xz',$xuanzhong);
		$this->assign('mxz',$meixuanzhong);
		$this->display();
	 
		return;
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-13
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：把用户添加到对应组【应用-模块-方法】
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	public function setUser()
	{
		$id     = I('groupUserId');
		$groupId	=	I('groupId');
		$group    =   D("Role");
		$group->delGroupUser($groupId);
		$result = $group->setGroupUsers($groupId,$id);
		if($result===false) {
			$this->ajaxerror('授权失败！');
		}else {
			$this->ajaxsuccess('授权成功!');
		}
	}
	
	public function _before_foreverdelete(){
		$id = I('id');
		$group    =   D("Role");
		$group->delGroupUser($id);
	}
	public function forbid(){
		$this->gforbid('status');
	}
	public function resume(){
		$this->gresume('status');
	}
}