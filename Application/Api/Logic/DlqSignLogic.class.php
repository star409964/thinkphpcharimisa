<?php
namespace Api\Logic;
use Api\Logic\WxSignLogic;
class DlqSignLogic extends WxSignLogic
{
	/**
	* 设置 jy_appid
	* @param string $value 
	**/
	public function SetAppid($value)
	{
		$this->values['jy_appid'] = $value;
	}
	/**
	* 获取 jy_appid
	* @return 值
	**/
	public function GetAppid()
	{
		return $this->values['jy_appid'];
	}
	/**
	* 判断jy_appid是否存在
	* @return true 或 false
	**/
	public function IsAppidSet()
	{
		return array_key_exists('jy_appid', $this->values);
	}


	/**
	* 设置支付时间戳
	* @param string $value 
	**/
	public function SetTimeStamp($value)
	{
		$this->values['timestamp'] = $value;
	}
	/**
	* 获取支付时间戳的值
	* @return 值
	**/
	public function GetTimeStamp()
	{
		return $this->values['timestamp'];
	}
	/**
	* 判断支付时间戳是否存在
	* @return true 或 false
	**/
	public function IsTimeStampSet()
	{
		return array_key_exists('timestamp', $this->values);
	}
	
	/**
	* 订单编号
	* @param string $value 
	**/
	public function SetCode($value)
	{
		$this->values['code'] = $value;
	}
	/**
	* 获取订单编号
	* @return 值
	**/
	public function GetCode()
	{
		return $this->values['code'];
	}
	/**
	* 判断订单编号是否存在
	* @return true 或 false
	**/
	public function IsReturn_codeSet()
	{
		return array_key_exists('code', $this->values);
	}


	/**
	* 设置订单流水号
	* @param string $value 
	**/
	public function SetTrade_no($value)
	{
		$this->values['trade_no'] = $value;
	}
	/**
	* 获取订单详情扩展字符串的值
	* @return 值
	**/
	public function GetTrade_no()
	{
		return $this->values['trade_no'];
	}
	/**
	* 判断订单详情扩展字符串是否存在
	* @return true 或 false
	**/
	public function IsTrade_noSet()
	{
		return array_key_exists('trade_no', $this->values);
	}
	
	/**
	* 设置签名方式
	* @param string $value 
	**/
	public function SetSignType($value)
	{
		$this->values['signType'] = $value;
	}
	/**
	* 获取签名方式
	* @return 值
	**/
	public function GetSignType()
	{
		return $this->values['signType'];
	}
	/**
	* 判断签名方式是否存在
	* @return true 或 false
	**/
	public function IsSignTypeSet()
	{
		return array_key_exists('signType', $this->values);
	}
	
	/**
	* 设置签名方式
	* @param string $value 
	**/
	public function SetPaySign($value)
	{
		$this->values['paySign'] = $value;
	}
	/**
	* 获取签名方式
	* @return 值
	**/
	public function GetPaySign()
	{
		return $this->values['paySign'];
	}
	/**
	* 判断签名方式是否存在
	* @return true 或 false
	**/
	public function IsPaySignSet()
	{
		return array_key_exists('paySign', $this->values);
	}
	
}
