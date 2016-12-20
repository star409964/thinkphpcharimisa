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
class RedisEvent
{
	protected $redis;
	
	
	 public function __construct($host='',$auth='',$port='6379',$timeout = 0.0){
    		if($host=='' ){
			$host=	C('REDIS_URL');
			$auth=	C('REDIS_AUTH');
		}
		$this->redis = new \Redis();
		$this->redis->connect($host, $port, $timeout);
		$this->redis->auth($auth);

		}
	/*
	 * 获取居游的票务订单信息
	 */	
	public function getTicketOrder($sysorder){
		
		$red = $this->redis->HgetAll(C('REDIS_ORDER_PREFIX').$sysorder);
		return $red;
	}
	/*
	 * 用户登录信息放入 redis里面
	 */
	 public function setUserInfo($info){
		$this->redis->hMset(C('REDIS_USER_PREFIX').$info['access_ticket'], $info);
		$this->redis->expire($info['access_ticket'],3600);
	 }
	 /*
	 * 追加用户信息到 redis里面
	 */
	 public function appendUserInfo($key,$ary){
		$list = $this->redis->HgetAll(C('REDIS_USER_PREFIX').$key);
		$info = array_merge($list,$ary);
		$this->setUserInfo($info);
	 }
	 /*
	 * 获取放入redis里面的用户信息
	 */
	 
	 public function getUserInfo($user_base){
		return $this->redis->HgetAll(C('REDIS_USER_PREFIX').$user_base);
		
	 }
	
}//class end
