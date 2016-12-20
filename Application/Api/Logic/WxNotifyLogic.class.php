<?php
/**
 * 微信的支付回调逻辑
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
use Payment\NotifyContext;
use Payment\Config; 
use Payment\Common\PayException;
use Api\Logic\WxChatBaseLogic;
class WxNotifyLogic extends WxChatBaseLogic {
	
	
	
	/*
	 * 微信 公共 回调
	 */ 
	 public function CommonNotify($wxid,$notifyurl){
			$wxconfig = $this->getConfig($wxid,$notifyurl);
			$notify = new NotifyContext();
			$callback = A('CommonNotify','Event');
			try {
			// 微信回调
			$notify->initNotify(Config::WEIXIN, $wxconfig);
			$ret = $notify->notify($callback);
			} catch (PayException $e) {
			SetLog('公共回调函数CommonNotify报错****错误信息'.$e->errorMessage(), 'notify.log');
			echo $e->errorMessage();exit;
			}
			return TRUE;exit;
		}
	
	
	/*
	 * 微信支付回调
	 */ 
	 public function Notify($wxid,$notifyurl){
			$wxconfig = $this->getConfig($wxid,$notifyurl);
			$notify = new NotifyContext();
			$callback = A('SkNotify','Event');
			try {
			// 微信回调
			$notify->initNotify(Config::WEIXIN, $wxconfig);
			$ret = $notify->notify($callback);
			} catch (PayException $e) {
			SetLog('公共回调函数WxNotify报错****错误信息'.$e->errorMessage(), 'notify.log');
			echo $e->errorMessage();exit;
			}
			return TRUE;exit;
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
    
   
	
	
}//class end
