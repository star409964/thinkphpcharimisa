<?php
/**
 * 微信的基础逻辑
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
class WxChatBaseLogic {
	static protected $accountList;
	//所有公众号列表

	function __construct() {
		self::$accountList = M("WxchatAccount") -> cache(TRUE) -> getField('id,token,appid,appsecret,encodingaeskey,mch_id,md5_key,cert_path,key_path');
		//dump(self::$accountList);
	}

	public function getAccessToken($id) {
		if($id==null){jsonReturn(110,'请传递公众号id');}
		$cacheT = S(array('type'=>'file','prefix'=>'wxtoken'.$id,'expire'=>7000));
		if (!$cacheT->token) {
			// 如果是企业号用以下URL获取access_token
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
			$appId = self::$accountList[$id]['appid'];
			$appSecret = self::$accountList[$id]['appsecret'];
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
			$res = json_decode(httpGet($url), TRUE);
			SetE($res);
			$access_token = $res['access_token'];
			if ($access_token) {
				$cacheT->token = $access_token;
			}
			//SetLog('获取accesstoken的时间-----------token='.$access_token,'accesstoken.log');
		} else {
			 $access_token = $cacheT->token; 
			//SetLog('当前accesstoken的-----------token='.$access_token,'nowaccesstoken.log');
		}
		trace($access_token,'此次的token');
		return $access_token;
	}

	public function getAccountList() {
		return self::$accountList;
	}

	/*
	 * 获取网页的access_token
	 */
	protected function getWyAccessToken($id, $code) {
		$appId = self::$accountList[$id]['appid'];
		$appSecret = self::$accountList[$id]['appsecret'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appId&secret=$appSecret&code=$code&grant_type=authorization_code";
		$access_token = json_decode(httpGet($url), TRUE);
		return $access_token;
	}

}
