<?php
namespace Api\Model;
use Api\Model\CommonModel;
class UserWxModel extends CommonModel {
	protected $connection = 'UAT_CAS';
	protected $tablePrefix = '';
//	public $_validate=array(
//			array('openid','','请直接关闭页面，无需重复授权',0,'unique',1)
//	);
	
	protected $_auto = array (        
	          array('user_base','uuid',1,'callback'), // 对name字段在新增和编辑的时候回调getName方法 
               );
	
	
}