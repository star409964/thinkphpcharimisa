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
class SkEvent
{
	/*
	 * 获取用户的标识id
	 */	
	public function getUserId($info){
		$mo = D("UserWx");
		$wx_user_info = $mo->where('openid='.$info['openid'])->find();
		if($wx_user_info!=FALSE){//存在数据
			return $wx_user_info['user_base'];
		}else{
			$info['user_base'] = createNonceStr();
			$res = $mo->add($info);
			if($res!=FALSE){
				return $info['user_base'];
			}
			
		}
	}
	/*
	 * 商客支付-【需要订单号orderid】
	 * 根据订单号 获取订单详情 -> 创建支付签名 -> 传递给前台js
	 * 
	 */
	public function skPay(){
		$wxid = I('post.wxid'); 
		isCheckWxid($wxid);
		$orderid = I('orderid');
		if(!empty($orderid)){
			$redis = A('Redis','Event');
			$orderInfo = $redis->getTicketOrder($orderid);
			$wxpay = A('WxPay','Logic');
			$notifyurl = U('Api/skNotify',array('wxid'=>1),'',TRUE);
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
	
	/*
	 * 绑定用户和商客信息
	 */
	public function bingWxUserSk($uuid,$code,$login_name){
		if($code!=null && $login_name!=null && $uuid!=null){
			$map['user_id'] = array('like',$uuid);
			$ret = D('UserWxSk')->where($map)->find();
			if($ret==FALSE){ 
				$mps['login_name'] = $login_name;
				$mps['code'] = $code;
				$rest = D('SysUser')->join('JOIN __SYS_OFFICE__ ON __SYS_USER__.office_id = __SYS_OFFICE__.id')->where($mps)->field('code')->find();
				if($rest!=false){//合法-存入对应表
					$data['company_user_name'] = $login_name;
					$data['company_code'] = $code;
					$data['user_id'] = $uuid;
					$mo = D('UserWxSk');
					$find = $mo->where($data)->find();
					if($find==FALSE){//没存在
						$rs = $mo->add($data);
						$session_key = session('wxopenid-ticket');
						if($session_key){// 把用户的对应关系 存入到redis里面
							$redis = A('Redis','Event');
							$ary = $redis->getUserInfo($session_key);
							$info = $redis->appendUserInfo($session_key,$ary);
						}
					}
					trace('插入数据库',$rs);
				}
			}
		}
		
	}
	/*
	 * 积分助力接口
	 */
	public function credits(){
		$adId = I('adid');
		$uuId = I('uuid');
		$topId = I('topid');
		if(!empty($adId ) && !empty($uuId) &&!empty($topId)){
			$map['ad_id'] = array('like',$adId);
			$adInfo = D("GenViewAd")->where($map)->getField('integral_num');
			trace($adInfo);
			if($adInfo!=FALSE){//存在这个广告
				$data['myuuid'] = $topId;
				$data['otheruuid'] = $uuId;
				$data['adid'] = $adId;
				$history = D("MerCreditsHelp")->where($data)->getField('id');
				if($history!=FALSE) jsonReturn(110,'您已经领取过积分了');
				$data['atime'] = toDate(time());
				$data['integral'] = $adInfo;
				$ret = D("MerCreditsHelp")->add($data);
				if($ret!=FALSE){
					D("GenViewAd")->where($map)->setInc('zan_num',1);;
					jsonReturn(1,'成功');
				}else{
					jsonReturn(110,'添加积分失败');
				}
			}else{
				jsonReturn(110,'没有这个广告');
			}
		}else{
			jsonReturn(110,'缺少参数');
		}
	}
	
	/*
	 * 谁给我 助力积分 列表
	 */
	 
	 public function creditsList(){
	 	$uuid = I('uuid');
		if(empty($uuid)) jsonReturn(110,'缺少参数');
		$map['myuuid'] = $uuid;
		$list = D("MerCreditsHelp")->join('LEFT JOIN __USER_WX__ ON __MER_CREDITS_HELP__.otheruuid = __USER_WX__.userid')->where($map)->field('integral,nickname,headimgurl')->select();
		if($list!=FALSE){
			jsonReturn(1,'返回数据成功',$list);
		}else{
			jsonReturn(1,'没有数据',0);
		}
	 }
	
	public function tt(){
		echo U('Api/skNotify',array('wxid'=>1),'',TRUE);
	}
	
}
