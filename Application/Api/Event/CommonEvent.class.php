<?php
/**
 * 商客的所有接口逻辑事件
 * ============================================================================
 * 版权所有 沈阳隆源兴网络科技有限公司，并保留所有权利。
 * 网站地址:
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: mike狼
 * Date: 2016-11-27
 */
namespace Api\Event;
class CommonEvent
{
	
	/*
	 * 公众号通用支付
	 * 根据订单号 获取订单详情 -> 创建支付签名 -> 传递给前台js
	 * 
	 */
	public function GzhPay(){
		$wxid = I('post.wxid'); 
		isCheckWxid($wxid);
		$orderid = I('orderid');
		if(!empty($orderid)){
			$redis = A('Redis','Event');
			$orderInfo = $redis->getTicketOrder($orderid); //去redis里面 取订单的详细信息
			$wxpay = A('WxPay','Logic');
			$notifyurl = U('Api/CommonNotify',array('wxid'=>1),'',TRUE);
			$payData = [
						"openid" =>session('wxopenid'),
					    "order_no"	=> $orderid,
					    "amount"	=> $orderInfo['total_fee'],// 单位为元 ,最小为0.01
					    "client_ip"	=> get_client_ip(),
					    "subject"	=> $orderInfo['subject'],
					    "body"	=> $orderInfo['subject'],
					    "extra_param"	=> $wxid,
					];
			$ret = $wxpay->gzhPay($wxid,$payData,$notifyurl);
			jsonReturn(1,'可以发起支付',$ret);
		}else{
			jsonReturn(110,'缺少参数-订单号');
		}
	}
	
	
	
	public function tt(){
		echo U('Api/skNotify',array('wxid'=>1),'',TRUE);
	}
	
}
