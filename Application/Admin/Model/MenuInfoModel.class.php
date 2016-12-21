<?php
namespace Admin\Model;
use Admin\Model\CommonModelModel;
class MenuInfoModel extends CommonModel {
	protected $tablePrefix = 'lyx_';
	protected $_validate = array(     
			 array('name','require','菜单名称不能空！'),  // 都有时间都验证    
			 array('sort','number','排序只能为数字！'),  // 只在登录时候验证     
			 );
	
	
	protected $_auto=array(
			array('path','getPath',3,'callback'),
	
	);
	
	function getPath(){
		$pid=$_POST['pid'];
		$mi=$this->field('id,path')->getById($pid);
		$path=$pid!='top'?0:$mi['path'].'-'.$mi['id'];
		return $path;
	}
	
	
	
	
}