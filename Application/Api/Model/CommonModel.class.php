<?php
namespace Api\Model;
use Think\Model;
class CommonModel extends Model {
	
//	protected $_auto = array (        
//	          array('id','uuid',1,'callback'), // 对name字段在新增和编辑的时候回调getName方法 
//             );
			   
	/* echo uuid(); //Returns like ‘1225c695-cfb8-4ebb-aaaa-80da344e8352′
	 * Using with prefix
	 * echo uuid(‘urn:uuid:’);//Returns like ‘urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344e8352′
	 * 
	 */
	public function uuid($prefix = '')  
	  {  
	    $chars = md5(uniqid(mt_rand(), true));  
	    $uuid  = substr($chars,0,8) . '-';  
	    $uuid .= substr($chars,8,4) . '-';  
	    $uuid .= substr($chars,12,4) . '-';  
	    $uuid .= substr($chars,16,4) . '-';  
	    $uuid .= substr($chars,20,12);  
	    return $prefix . $uuid;  
	  } 
	
	// 获取当前用户的ID
	public function getMemberId() {
		return isset($_SESSION[C('USER_AUTH_KEY')])?$_SESSION[C('USER_AUTH_KEY')]:0;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据条件禁用表数据
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param array $options 条件
	 +----------------------------------------------------------
	 * @return boolen
	 +----------------------------------------------------------
	 */
	public function forbid($options,$field='status'){
	
		if(FALSE === $this->where($options)->setField($field,0)){
			$this->error =  L('_OPERATION_WRONG_');
			return false;
		}else {
			return True;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据条件批准表数据
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param array $options 条件
	 +----------------------------------------------------------
	 * @return boolen
	 +----------------------------------------------------------
	 */
	
	public function checkPass($options,$field='status'){
		if(FALSE === $this->where($options)->setField($field,1)){
			$this->error =  L('_OPERATION_WRONG_');
			return false;
		}else {
			return True;
		}
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 根据条件恢复表数据
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param array $options 条件
	 +----------------------------------------------------------
	 * @return boolen
	 +----------------------------------------------------------
	 */
	public function resume($options,$field='status'){
		if(FALSE === $this->where($options)->setField($field,1)){
			$this->error =  L('_OPERATION_WRONG_');
			return false;
		}else {
			return True;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据条件恢复表数据
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param array $options 条件
	 +----------------------------------------------------------
	 * @return boolen
	 +----------------------------------------------------------
	 */
	public function recycle($options,$field='status'){
		if(FALSE === $this->where($options)->setField($field,0)){
			$this->error =  L('_OPERATION_WRONG_');
			return false;
		}else {
			return True;
		}
	}
	
	public function recommend($options,$field='is_recommend'){
		if(FALSE === $this->where($options)->setField($field,1)){
			$this->error =  L('_OPERATION_WRONG_');
			return false;
		}else {
			return True;
		}
	}
	
	public function unrecommend($options,$field='is_recommend'){
		if(FALSE === $this->where($options)->setField($field,0)){
			$this->error =  L('_OPERATION_WRONG_');
			return false;
		}else {
			return True;
		}
	}
}