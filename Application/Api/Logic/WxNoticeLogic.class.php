<?php
/**
 * 微信的消息通知逻辑
 * ============================================================================
 * 版权所有 沈阳隆源兴网络科技有限公司，并保留所有权利。
 * 网站地址:
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: mike狼
 * Date: 2016-12-13
 */
namespace Api\Logic;
use Api\Logic\WxChatBaseLogic;
class WxNoticeLogic extends WxChatBaseLogic {
	
	
	/*
	  * 发送微信的模版消息
	  * [str]-需要转正json形式
	  * 文档说明地址：https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277&token=&lang=zh_CN
	  */
	 public function sendTemplate($srt){
	 	$token = $this->getAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$token;
		$res = json_decode($this->httpPost($url,$srt),TRUE); 
		return $res;
	 }
	 
	 
	 public function wxSendNotice($data1,$data2,$data3,$data4,$sign){
	 	$wxNotice = A('WxNotice','Logic');
		$data['first'] = array('value'=>$data1);
		$data['keyword1'] = array('value'=>$data2);
		$data['keyword2'] = array('value'=>$data3);
		$data['remark'] = array('value'=>$data4);
		$pid = M('NoticeCategory')->where('str="'.$sign.'"')->getField('id');
		if($pid==FALSE) jsonReturn('3','请去后台添加接口');
		$list = M('NoticeUsers')->where('pid='.$pid)->select();
		if($list==FALSE) jsonReturn('4','请去后台给接口添加人员');
		
		foreach ($list as $key => $value) {
			//居游的消息id
			$wxNotice->notice100($value['openid'],'9b4hYEgca9uJ7pCQ0gpEideLgWNbuKCw6fkVgBp-umw',$data);
			//萌叔的消息id
			//$wxNotice->notice100($value['openid'],'vQSIKx6eqZxpmMfi54c-Mueg04noF27pHuYOzbSckLc',$data);
		}
	 }
	
	
}//class end
