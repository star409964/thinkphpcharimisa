<?php
namespace Admin\Model;
use Admin\Model\CommonModel;
class RoleModel extends CommonModel {
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：自动验证 
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	public $_validate = array(
			array('name','require','名称必须'),
	);
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：自动对数据库字段进行更新插入
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	public $_auto		=	array(
			array('create_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_UPDATE,'function'),
	);
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：获取node 节点数据 根据access 的  node_id=  node权限表的id
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	function getGroupActionList($groupId,$moduleId)
	{
	    $table = $this->tablePrefix.'access';
	    $rs = $this->db->query('select b.id,b.title,b.name from '.$table.' as a ,'.$this->tablePrefix.'node as b where a.node_id=b.id and  b.pid='.$moduleId.' and  a.role_id='.$groupId.' ');
	    return $rs;
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：获取node 节点数据 根据access 的  node_id=  node权限表的id
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */	
	function getGroupAppList($groupId)
	{
		$rs = $this->db->query('select b.id,b.title,b.name from '.$this->tablePrefix.'access as a ,'.$this->tablePrefix.'node as b where a.node_id=b.id and  b.pid=0 and a.role_id='.$groupId.' ');
		return $rs;
	}
	/**
	 +----------------------------------------------------------
	 * 创建人：mike
	 * 创建时间：2015-8-7
	 * 修改时间：
	 * 修改内容：
	 +----------------------------------------------------------
	 * 函数说明：获取node 节点数据 根据access 的  node_id=  node权限表的id
	 +----------------------------------------------------------
	 * 函数属性：
	 +----------------------------------------------------------
	 * 备注：
	 +----------------------------------------------------------
	 */
	function getGroupModuleList($groupId,$appId)
	{
		$table = $this->tablePrefix.'access';
		$rs = $this->db->query('select b.id,b.title,b.name from '.$table.' as a ,'.$this->tablePrefix.'node as b where a.node_id=b.id and  b.pid='.$appId.' and a.role_id='.$groupId.' ');
		return $rs;
	}
	function delGroupModule($groupId,$appId)
	{
		$table = $this->tablePrefix.'access';
		$result = $this->db->execute('delete from '.$table.' where level=2 and pid='.$appId.' and role_id='.$groupId);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}
	function setGroupModules($groupId,$moduleIdList)
	{
		if(empty($moduleIdList)) {
			return true;
		}
		if(is_array($moduleIdList)) {
			$moduleIdList = implode(',',$moduleIdList);
		}
		$where = 'a.id ='.$groupId.' AND b.id in('.$moduleIdList.')';
		$rs = $this->db->execute('INSERT INTO '.$this->tablePrefix.'access (role_id,node_id,pid,level) SELECT a.id, b.id,b.pid,b.level FROM '.$this->tablePrefix.'role a, '.$this->tablePrefix.'node b WHERE '.$where);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}
	function delGroupApp($groupId)
	{
		$table = $this->tablePrefix.'access';
		$result = $this->db->execute('delete from '.$table.' where level=1 and role_id='.$groupId);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}
	function setGroupApps($groupId,$appIdList)
	{
		if(empty($appIdList)) {
			return true;
		}
		$id = implode(',',$appIdList);
		$where = 'a.id ='.$groupId.' AND b.id in('.$id.')';
		$result = $this->db->execute('INSERT INTO '.$this->tablePrefix.'access (role_id,node_id,pid,level) SELECT a.id, b.id,b.pid,b.level FROM '.$this->tablePrefix.'role a, '.$this->tablePrefix.'node b WHERE '.$where);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}
	
	
	function setGroupActions($groupId,$actionIdList)
	{
		if(empty($actionIdList)) {
			return true;
		}
		if(is_array($actionIdList)) {
			$actionIdList = implode(',',$actionIdList); //数组变成 字符串
		}
		$where = 'a.id ='.$groupId.' AND b.id in('.$actionIdList.')';
		$rs = $this->db->execute('INSERT INTO '.$this->tablePrefix.'access (role_id,node_id,pid,level) SELECT a.id, b.id,b.pid,b.level FROM '.$this->tablePrefix.'role a, '.$this->tablePrefix.'node b WHERE '.$where);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}
	function delGroupAction($groupId,$moduleId)
	{
		$table = $this->tablePrefix.'access';
	
		$result = $this->db->execute('delete from '.$table.' where level=3 and pid='.$moduleId.' and role_id='.$groupId);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}
	function getGroupUserList($groupId)
	{
		$table = $this->tablePrefix.'role_user';
		$rs = $this->db->query('select b.id,b.nickname,b.email from '.$table.' as a ,'.$this->tablePrefix.'user as b where a.user_id=b.id and  a.role_id='.$groupId.' ');
		return $rs;
	}
	function delGroupUser($groupId)
	{
		$table = $this->tablePrefix.'role_user';
	
		$result = $this->db->execute('delete from '.$table.' where role_id='.$groupId);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}
	function setGroupUsers($groupId,$userIdList)
	{
		if(empty($userIdList)) {
			return true;
		}
		if(is_string($userIdList)) {
			$userIdList = explode(',',$userIdList);
		}
		array_walk($userIdList, array($this, 'fieldFormat'));
		$userIdList	 =	 implode(',',$userIdList);
		$where = 'a.id ='.$groupId.' AND b.id in('.$userIdList.')';
		$rs = $this->execute('INSERT INTO '.$this->tablePrefix.'role_user (role_id,user_id) SELECT a.id, b.id FROM '.$this->tablePrefix.'role a, '.$this->tablePrefix.'user b WHERE '.$where);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}
	
	protected function fieldFormat(&$value)
	{
		if(is_int($value)) {
			$value = intval($value);
		} else if(is_float($value)) {
			$value = floatval($value);
		}else if(is_string($value)) {
			$value = '"'.addslashes($value).'"';
		}
		return $value;
	}
}