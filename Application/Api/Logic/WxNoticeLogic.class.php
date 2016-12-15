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
	 private function sendTemplate($wxid,$srt){
	 	$token = $this->getAccessToken($wxid);
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$token;
		$res = json_decode(httpPost($url,$srt),TRUE); 
		return $res;
	 }
	 
    
    private function _sendMsg($wxid,$data){
    		$res = $this->sendTemplate($wxid,$data);
		if($res['errcode']!=0){//异常处理
		 	jsonReturn('110',ReturnWxError($res));
		 }else{
		 	jsonReturn(1,'发送成功');
		 }
    }
	
	 /*
	  *对外 暴露的消息通知接口【公司内部调用】 
	  */
	 public function wxSendNotice($wxid,$info,$sign,$fun){
		$pid = M('NoticeCategory')->where('str="'.$sign.'"')->getField('id');
		if($pid==FALSE) jsonReturn('110','请去后台添加接口');
		$list = M('NoticeUsers')->where('pid='.$pid)->select();
		if($list==FALSE) jsonReturn('110','请去后台给接口添加人员');
		
		if (!method_exists ( $this, $fun )) {
			jsonReturn('110','没有此函数');
		}
		$map['accountid'] = $wxid;
		$map['tplfun'] = $fun;
		
		$noticeInfo = M('WxchatAccountNotice')->join('JOIN __WXCHAT_NOTICE__ ON __WXCHAT_ACCOUNT_NOTICE__.noticeid = __WXCHAT_NOTICE__.id')->where($map)->find();
		foreach ($list as $key => $value) {
			if (method_exists ( $this, $fun )) {
				$this->$fun ($wxid, $value['openid'],$noticeInfo['tplid'],$info );
			}else{
				jsonReturn('110','没有此函数');
			}	
		}
	 }
	 
	 /*
	  * 错误通知
	  * 
	  * {{first.DATA}}
		系统名称：{{keyword1.DATA}}
		错误信息：{{keyword2.DATA}}
		{{remark.DATA}}
	  * 
	  * 您好，系统发生了一个错误
		系统名称：支付系统
		错误信息：服务器关闭，支付不成功
		请您及时处理该错误
	  * 
	  */
    public function notice100($wxid,$openid,$tplid,$info,$url=null)
    {
    	
		$datas['first'] = array('value'=>$info[0]);
		$datas['keyword1'] = array('value'=>$info[1]);
		$datas['keyword2'] = array('value'=>$info[2]);
		$datas['remark'] = array('value'=>$info[3]);
			
		$data['touser'] = $openid;
		$data['template_id'] = $tplid;
		if($url!=null)$data['url'] = $url;
		$data['data'] = $datas;
		$dat = json_encode($data);
		$this->_sendMsg($wxid,$dat);
    }
	 
	 
	 
	
	
}//class end
