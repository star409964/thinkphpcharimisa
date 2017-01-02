<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class RuleKeywordController extends CommonController {
	
	public function _filter(&$map)
	{
	    $map['wxid'] = getWxid();
		$search = I('search',110119120);
		if($search!='110119120'){
			$map['title'] = array('like','%'.$search.'%');
		}
	}
	
	public function tt($id){
		$wxChat = A("Api/WxChat","Logic");
		$wxChat->getMediasImage('fvVr-DwM6S11H_a-sEnr6pnYKTRTGA5GcObGlZlNwrg',1);//微信号，类型
	}
	
}//class end	