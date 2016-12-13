<?php
/**
 * 微信的支付逻辑
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
namespace Api\Logic;
Vendor('payment-paymentv2.autoload');
use Payment\ChargeContext;
use Payment\Config; 
use Payment\Common\PayException;
use Api\Logic\WxChatBaseLogic;
class WxPayLogic extends WxChatBaseLogic {
	
	
	/*
	 * 公众号支付
	 * 【wxchat_account微信号id】【订单信息】【回掉的地址】
	 */
	public function gzhPay($wxid,$payData,$notifyurl){
		// 订单信息
//		$payData = [
//			"openid" =>'oaugowRfzIQDRqk0T4GCi3F1LxzE',
//		    "order_no"	=> $this->createPayid(),
//		    "amount"	=> '0.01',// 单位为元 ,最小为0.01
//		    "client_ip"	=> '127.0.0.1',
//		    "subject"	=> '测试支付',
//		    "body"	=> '支付接口测试',
//		    "extra_param"	=> '',
//		];
//		
//		$notifyurl = U("Api/notify");
		$wxconfig = $this->getConfig($wxid,$notifyurl);
		
		/**
		 * 实例化支付环境类，进行支付创建
		 */
		$charge = new ChargeContext();
		
		try {
		    // 微信 公众号支付
		    $type = Config::WX_CHANNEL_PUB;
		    $charge->initCharge($type, $wxconfig);
		    $ret = $charge->charge($payData);
		} catch (PayException $e) {
		    echo $e->errorMessage();exit;
		}
		  return json_decode($ret,JSON_UNESCAPED_UNICODE); 
		 // return $ret;
		
	}
    
	
	private function getConfig($wxid,$notifyurl){
		return [
			    'app_id'    => self::$accountList[$wxid]['appid'],  // 公众账号ID
			    'mch_id'    => self::$accountList[$wxid]['mch_id'],// 商户id
			    'md5_key'   => self::$accountList[$wxid]['md5_key'],// md5 秘钥
			    'notify_url'    => $notifyurl,
			    'time_expire'	=> '14',
			    // 涉及资金流动时 退款  转款，需要提供该文件
			    'cert_path' => self::$accountList[$wxid]['cert_path'],
			    'key_path'  => self::$accountList[$wxid]['key_path'],
			];
	}
    
    //  生成订单号 便于测试
	function createPayid()
	{
	    return date('Ymdhis', time()).substr(floor(microtime()*1000),0,1).rand(0,9);
	}
	
	
}//class end
