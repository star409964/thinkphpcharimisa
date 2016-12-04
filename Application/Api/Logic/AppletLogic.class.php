<?php
/**
 * 小程序后端逻辑程序
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

namespace Index\Logic;
Vendor("WxXcxDecode.wxBizDataCrypt");//加密数据解析
class AppletLogic 
{
	protected $appid; //小程序的appid
	protected $AppSecret;//小程序的加密
	
	/**
	 * 初始化数据
	 *
	 * @return OssClient 一个OssClient实例
	 */
    public function __construct()
    {
		$this->appid =  getAppid();    
		$this->AppSecret = getAppSecret(); 
    }
	
	/**
	 * 微信小程序登陆
	 * 2016-11-28
	 * @param sessionid 用户登陆标识
	 * @param encryptedData 要解析的数据
	 * @param iv 对称解密算法初始向量 iv 
	 * @return json
	 */
	 public function login(){
	 	
	 }
	
	/**
	 * 微信小程序解密
	 * 2016-11-28
	 * @param sessionid 用户登陆标识
	 * @param encryptedData 要解析的数据
	 * @param iv 对称解密算法初始向量 iv 
	 * @return json
	 */
	public function wxXcxDecode($encryptedData,$iv,$sessionid){
		Vendor("WxXcxDecode.wxBizDataCrypt");
		$appid = $this->appid;
		$sessionKey = $this->getSessionKey($sessionid);
		$pc = new \WXBizDataCrypt($appid, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $data );
		if ($errCode == 0) {
		    jsonReturn(1,'解析成功',$data);
		} else {
			jsonReturn(999,'errorCode='.$errCode);
		}
	}
	/**
	 * 微信小程序获取登陆key
	 * 2016-11-28
	 * @return string
	 */
	public function getSessionKey($key){
		return S($key);
	}
    
}