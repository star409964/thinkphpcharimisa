<?php
require_once "WxPay.Data.php";

class RedPackData extends WxPayDataBase{
	/**
	* 设置微信分配的公众账号ID
	* @param string $value 
	**/
	public function SetAppid($value)
	{
		$this->values['wxappid'] = $value;
	}
	/**
	* 获取微信分配的公众账号ID的值
	* @return 值
	**/
	public function GetAppid()
	{
		return $this->values['wxappid'];
	}
	/**
	* 判断微信分配的公众账号ID是否存在
	* @return true 或 false
	**/
	public function IsAppidSet()
	{
		return array_key_exists('wxappid', $this->values);
	}


	/**
	* 设置微信支付分配的商户号
	* @param string $value 
	**/
	public function SetMch_id($value)
	{
		$this->values['mch_id'] = $value;
	}
	/**
	* 获取微信支付分配的商户号的值
	* @return 值
	**/
	public function GetMch_id()
	{
		return $this->values['mch_id'];
	}
	/**
	* 判断微信支付分配的商户号是否存在
	* @return true 或 false
	**/
	public function IsMch_idSet()
	{
		return array_key_exists('mch_id', $this->values);
	}
	
	/**
	* 设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
	* @param string $value 
	**/
	public function SetOut_mac_billno($value)
	{
		$this->values['mch_billno'] = $value;
	}
	/**
	* 获取商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号的值
	* @return 值
	**/
	public function GetOut_mac_billno()
	{
		return $this->values['mch_billno'];
	}
	/**
	* 判断商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号是否存在
	* @return true 或 false
	**/
	public function IsOut_mac_billno()
	{
		return array_key_exists('mch_billno', $this->values);
	}
	
	
	/**
	* 设置商户名称
	* @param string $value 
	**/
	public function SetSend_name($value)
	{
		$this->values['send_name'] = $value;
	}
	/**
	* 获取商户名称，不长于32位。
	* @return 值
	**/
	public function GetSend_name()
	{
		return $this->values['send_name'];
	}
	/**
	* 判断商户名称，不长于32位。
	* @return true 或 false
	**/
	public function IsSend_name()
	{
		return array_key_exists('send_name', $this->values);
	}
	
	/**
	* 设置接受红包的用户,用户在wxappid下的openid
	* @param string $value 
	**/
	public function SetRe_openid($value)
	{
		$this->values['re_openid'] = $value;
	}
	/**
	* 获取接受红包的用户,用户在wxappid下的openid，不长于32位。
	* @return 值
	**/
	public function GetRe_openid()
	{
		return $this->values['re_openid'];
	}
	/**
	* 判断接受红包的用户,用户在wxappid下的openid，不长于32位。
	* @return true 或 false
	**/
	public function IsRe_openid()
	{
		return array_key_exists('re_openid', $this->values);
	}
	/**
	* 设置付款金额，单位分
	* @param int $value 
	**/
	public function SetTotal_amount($value)
	{
		$this->values['total_amount'] = $value;
	}
	/**
	* 获取付款金额，单位分。
	* @return 值
	**/
	public function GetTotal_amount()
	{
		return $this->values['total_amount'];
	}
	/**
	* 判断付款金额，单位分。
	* @return true 或 false
	**/
	public function IsTotal_amount()
	{
		return array_key_exists('total_amount', $this->values);
	}
	
	/**
	* 设置红包发放总人数 total_num=1,默认为1
	* @param int $value 
	**/
	public function SetTotal_num($value=1)
	{
		$this->values['total_num'] = $value;
	}
	/**
	* 获取红包发放总人数。
	* @return 值
	**/
	public function GetTotal_num()
	{
		return $this->values['total_num'];
	}
	/**
	* 判断红包发放总人数。
	* @return true 或 false
	**/
	public function IsTotal_num()
	{
		return array_key_exists('total_num', $this->values);
	}
	
	/**
	* 设置 红包祝福语
	* @param string $value 
	**/
	public function Set_wishing($value)
	{
		$this->values['wishing'] = $value;
	}
	/**
	* 获取红包祝福语。
	* @return 值
	**/
	public function Get_wishing()
	{
		return $this->values['wishing'];
	}
	/**
	* 判断红包祝福语。
	* @return true 或 false
	**/
	public function Is_wishing()
	{
		return array_key_exists('wishing', $this->values);
	}
	
	/**
	* 设置调用接口的机器Ip地址。
	* @param string $value 
	**/
	public function SetClient_ip($value)
	{
		$this->values['client_ip'] = $value;
	}
	/**
	* 获取调用接口的机器Ip地址
	* @return 值
	**/
	public function GetClient_ip()
	{
		return $this->values['client_ip'];
	}
	/**
	* 判断 调用接口的机器Ip地址
	* @return true 或 false
	**/
	public function IsClient_ip()
	{
		return array_key_exists('client_ip', $this->values);
	}
	
	/**
	* 设置 活动名称。
	* @param string $value 
	**/
	public function SetAct_name($value)
	{
		$this->values['act_name'] = $value;
	}
	/**
	* 获取活动名称
	* @return 值
	**/
	public function GetAct_name()
	{
		return $this->values['act_name'];
	}
	/**
	* 判断 活动名称
	* @return true 或 false
	**/
	public function IsAct_name()
	{
		return array_key_exists('act_name', $this->values);
	}
	
	/**
	* 设置 备注信息。
	* @param string $value 
	**/
	public function Set_remark($value)
	{
		$this->values['remark'] = $value;
	}
	/**
	* 获取备注信息
	* @return 值
	**/
	public function Get_remark()
	{
		return $this->values['remark'];
	}
	/**
	* 判断 备注信息
	* @return true 或 false
	**/
	public function Is_remark()
	{
		return array_key_exists('remark', $this->values);
	}
	
	/**
	* 设置随机字符串，不长于32位。推荐随机数生成算法
	* @param string $value 
	**/
	public function SetNonce_str($value)
	{
		$this->values['nonce_str'] = $value;
	}
	/**
	* 获取随机字符串，不长于32位。推荐随机数生成算法的值
	* @return 值
	**/
	public function GetNonce_str()
	{
		return $this->values['nonce_str'];
	}
	/**
	* 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
	* @return true 或 false
	**/
	public function IsNonce_strSet()
	{
		return array_key_exists('nonce_str', $this->values);
	}
}
	
	
class gethbInfoData extends WxPayDataBase{
	/**
	* 设置微信分配的公众账号ID
	* @param string $value 
	**/
	public function SetAppid($value)
	{
		$this->values['appid'] = $value;
	}
	/**
	* 获取微信分配的公众账号ID的值
	* @return 值
	**/
	public function GetAppid()
	{
		return $this->values['appid'];
	}
	/**
	* 判断微信分配的公众账号ID是否存在
	* @return true 或 false
	**/
	public function IsAppidSet()
	{
		return array_key_exists('appid', $this->values);
	}


	/**
	* 设置微信支付分配的商户号
	* @param string $value 
	**/
	public function SetMch_id($value)
	{
		$this->values['mch_id'] = $value;
	}
	/**
	* 获取微信支付分配的商户号的值
	* @return 值
	**/
	public function GetMch_id()
	{
		return $this->values['mch_id'];
	}
	/**
	* 判断微信支付分配的商户号是否存在
	* @return true 或 false
	**/
	public function IsMch_idSet()
	{
		return array_key_exists('mch_id', $this->values);
	}
	
	/**
	* 设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
	* @param string $value 
	**/
	public function SetOut_mac_billno($value)
	{
		$this->values['mch_billno'] = $value;
	}
	/**
	* 获取商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号的值
	* @return 值
	**/
	public function GetOut_mac_billno()
	{
		return $this->values['mch_billno'];
	}
	/**
	* 判断商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号是否存在
	* @return true 或 false
	**/
	public function IsOut_mac_billno()
	{
		return array_key_exists('mch_billno', $this->values);
	}
	
	
	/**
	* 设置订单类型
	* @param string $value 
	**/
	public function SetBill_type($value='MCHT')
	{
		$this->values['bill_type'] = $value;
	}
	/**
	* 获取订单类型。
	* @return 值
	**/
	public function GetBill_type()
	{
		return $this->values['bill_type'];
	}
	/**
	* 判断订单类型。
	* @return true 或 false
	**/
	public function IsBill_type()
	{
		return array_key_exists('bill_type', $this->values);
	}
	
	
	/**
	* 设置随机字符串，不长于32位。推荐随机数生成算法
	* @param string $value 
	**/
	public function SetNonce_str($value)
	{
		$this->values['nonce_str'] = $value;
	}
	/**
	* 获取随机字符串，不长于32位。推荐随机数生成算法的值
	* @return 值
	**/
	public function GetNonce_str()
	{
		return $this->values['nonce_str'];
	}
	/**
	* 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
	* @return true 或 false
	**/
	public function IsNonce_strSet()
	{
		return array_key_exists('nonce_str', $this->values);
	}
}	

	
class AppApiPayData extends WxPayDataBase{
	/**
	* 设置微信分配的公众账号ID
	* @param string $value 
	**/
	public function SetAppid($value)
	{
		$this->values['appid'] = $value;
	}
	/**
	* 获取微信分配的公众账号ID的值
	* @return 值
	**/
	public function GetAppid()
	{
		return $this->values['appid'];
	}
	/**
	* 判断微信分配的公众账号ID是否存在
	* @return true 或 false
	**/
	public function IsAppidSet()
	{
		return array_key_exists('appid', $this->values);
	}


	/**
	* 设置微信支付分配的商户号
	* @param string $value 
	**/
	public function SetMch_id($value)
	{
		$this->values['partnerid'] = $value;
	}
	/**
	* 获取微信支付分配的商户号的值
	* @return 值
	**/
	public function GetMch_id()
	{
		return $this->values['partnerid'];
	}
	/**
	* 判断微信支付分配的商户号是否存在
	* @return true 或 false
	**/
	public function IsMch_idSet()
	{
		return array_key_exists('partnerid', $this->values);
	}
	
	/**
	* 设置微信返回的支付交易会话ID
	* @param string $value 
	**/
	public function SetPrepayid($value)
	{
		$this->values['prepayid'] = $value;
	}
	/**
	* 获取微信返回的支付交易会话ID
	* @return 值
	**/
	public function GetPrepayid()
	{
		return $this->values['prepayid'];
	}
	/**
	* 判断微信返回的支付交易会话ID
	* @return true 或 false
	**/
	public function IsPrepayid()
	{
		return array_key_exists('prepayid', $this->values);
	}
	
	
	/**
	* 设置订单类型
	* @param string $value 
	**/
	public function SetPackage($value='Sign=WXPay')
	{
		$this->values['package'] = $value;
	}
	/**
	* 获取订单类型。
	* @return 值
	**/
	public function GetPackage()
	{
		return $this->values['package'];
	}
	/**
	* 判断订单类型。
	* @return true 或 false
	**/
	public function IsPackage()
	{
		return array_key_exists('package', $this->values);
	}
	
	/**
	* 设置支付时间戳
	* @param string $value 
	**/
	public function SetTimeStamp($value)
	{
		$this->values['timeStamp'] = $value;
	}
	/**
	* 获取支付时间戳的值
	* @return 值
	**/
	public function GetTimeStamp()
	{
		return $this->values['timeStamp'];
	}
	/**
	* 判断支付时间戳是否存在
	* @return true 或 false
	**/
	public function IsTimeStampSet()
	{
		return array_key_exists('timeStamp', $this->values);
	}
	
	/**
	* 设置随机字符串，不长于32位。推荐随机数生成算法
	* @param string $value 
	**/
	public function SetNoncestr($value)
	{
		$this->values['noncestr'] = $value;
	}
	/**
	* 获取随机字符串，不长于32位。推荐随机数生成算法的值
	* @return 值
	**/
	public function GetNoncestr()
	{
		return $this->values['noncestr'];
	}
	/**
	* 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
	* @return true 或 false
	**/
	public function IsNoncestrSet()
	{
		return array_key_exists('noncestr', $this->values);
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

//商户付款到用户
class PushMoneyData extends WxPayDataBase{
	/**
	* 设置微信分配的公众账号ID
	* @param string $value 
	**/
	public function SetAppid($value)
	{
		$this->values['mch_appid'] = $value;
	}
	/**
	* 获取微信分配的公众账号ID的值
	* @return 值
	**/
	public function GetAppid()
	{
		return $this->values['mch_appid'];
	}
	/**
	* 判断微信分配的公众账号ID是否存在
	* @return true 或 false
	**/
	public function IsAppidSet()
	{
		return array_key_exists('mch_appid', $this->values);
	}


	/**
	* 设置微信支付分配的商户号
	* @param string $value 
	**/
	public function SetMch_id($value)
	{
		$this->values['mchid'] = $value;
	}
	/**
	* 获取微信支付分配的商户号的值
	* @return 值
	**/
	public function GetMch_id()
	{
		return $this->values['mchid'];
	}
	/**
	* 判断微信支付分配的商户号是否存在
	* @return true 或 false
	**/
	public function IsMch_idSet()
	{
		return array_key_exists('mchid', $this->values);
	}
	
	/**
	* 设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
	* @param string $value 
	**/
	public function SetOut_mac_billno($value)
	{
		$this->values['partner_trade_no'] = $value;
	}
	/**
	* 获取商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号的值
	* @return 值
	**/
	public function GetOut_mac_billno()
	{
		return $this->values['partner_trade_no'];
	}
	/**
	* 判断商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号是否存在
	* @return true 或 false
	**/
	public function IsOut_mac_billno()
	{
		return array_key_exists('partner_trade_no', $this->values);
	}
	
	
	/**
	* 设置接受红包的用户,用户在wxappid下的openid
	* @param string $value 
	**/
	public function Setopenid($value)
	{
		$this->values['openid'] = $value;
	}
	/**
	* 获取接受红包的用户,用户在wxappid下的openid，不长于32位。
	* @return 值
	**/
	public function Getopenid()
	{
		return $this->values['openid'];
	}
	/**
	* 判断接受红包的用户,用户在wxappid下的openid，不长于32位。
	* @return true 或 false
	**/
	public function Isopenid()
	{
		return array_key_exists('openid', $this->values);
	}
	/**
	* 设置付款金额，单位分
	* @param int $value 
	**/
	public function SetTotal_amount($value)
	{
		$this->values['amount'] = $value;
	}
	/**
	* 获取付款金额，单位分。
	* @return 值
	**/
	public function GetTotal_amount()
	{
		return $this->values['amount'];
	}
	/**
	* 判断付款金额，单位分。
	* @return true 或 false
	**/
	public function IsTotal_amount()
	{
		return array_key_exists('amount', $this->values);
	}
	
	
	
	/**
	* 设置 企业付款描述信息
	* @param string $value 
	**/
	public function Set_desc($value)
	{
		$this->values['desc'] = $value;
	}
	/**
	* 获取企业付款描述信息。
	* @return 值
	**/
	public function Get_desc()
	{
		return $this->values['desc'];
	}
	/**
	* 判断企业付款描述信息。
	* @return true 或 false
	**/
	public function Is_desc()
	{
		return array_key_exists('desc', $this->values);
	}
	
	/**
	* 设置调用接口的机器Ip地址。
	* @param string $value 
	**/
	public function SetClient_ip($value)
	{
		$this->values['spbill_create_ip'] = $value;
	}
	/**
	* 获取调用接口的机器Ip地址
	* @return 值
	**/
	public function GetClient_ip()
	{
		return $this->values['spbill_create_ip'];
	}
	/**
	* 判断 调用接口的机器Ip地址
	* @return true 或 false
	**/
	public function IsClient_ip()
	{
		return array_key_exists('spbill_create_ip', $this->values);
	}
	
	/**
	* 设置 校验用户姓名选项
	* @param string $value 
	**/
	public function SetCheck_name($value)
	{
		$this->values['check_name'] = $value;
	}
	/**
	* 获取校验用户姓名选项
	* @return 值
	**/
	public function GetCheck_name()
	{
		return $this->values['check_name'];
	}
	/**
	* 判断 校验用户姓名选项
	* @return true 或 false
	**/
	public function IsCheck_name()
	{
		return array_key_exists('check_name', $this->values);
	}
	

	/**
	* 设置随机字符串，不长于32位。推荐随机数生成算法
	* @param string $value 
	**/
	public function SetNonce_str($value)
	{
		$this->values['nonce_str'] = $value;
	}
	/**
	* 获取随机字符串，不长于32位。推荐随机数生成算法的值
	* @return 值
	**/
	public function GetNonce_str()
	{
		return $this->values['nonce_str'];
	}
	/**
	* 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
	* @return true 或 false
	**/
	public function IsNonce_strSet()
	{
		return array_key_exists('nonce_str', $this->values);
	}
}
	