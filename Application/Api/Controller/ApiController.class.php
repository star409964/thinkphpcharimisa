<?php
/**
 * lyx
 * ============================================================================
 * 版权所有 2016-2030 沈阳隆源兴网络科技有限公司，并保留所有权利。
 * 网站地址: http://sylyx.cn
 * ----------------------------------------------------------------------------
 * 所有接口的 默认进入 端口
 * ============================================================================
 * Author: mike<stardandan@126.com>
 * Date: 2016-11-21
 */
namespace Api\Controller;
use Think\Controller;
class ApiController extends Controller {
	
	function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');
	}
	
	/*
	 * 微信接入 地址 带上id=
	 */
	public function index(){
		$wx = A("WxChat",'Logic');
		$wx->valid();
	}	
	/*
	 * 网页授权
	 */
	public function authBase(){
		$all = I('get.');
		$wxid = I('wxid');
		$rurl = '';
		foreach ($all as $key => $value) {
			if($key=='rurl'){
				$rurl = $value;
			}else{
				$rurl = $rurl.'&'.$key.'='.$value;
			}
		}
		$ts = time().rand(1000, 9999);
		F($ts,$rurl);
		$myurl = C('HD_BASE_URL').U('requestUrl',array('wxid'=>$wxid)); 
		$wechatObj = A('WxChat','Logic');
		$res = $wechatObj->getcodeUrl($ts,$myurl,$wxid);
		redirect($res);
	}
	
	/**
	 * 函数说明：跳转带上原来的参数
	 * @deprecated 创建时间：2016-2-6
	 * @deprecated 备注：
	 * @author mike<stardandan@126.com>
	 * @return redirect
	 */
	public function requestUrl(){
		$code = I('code');
		$wxid = I('wxid');
		$state = I('state');
		$resurl = '';
		$wechatObj = A('WxChat','Logic');
		$token_openid = $wechatObj->getWyInfo($code,$wxid);
		$res = $wechatObj->WxError($token_openid);
		if($res==FALSE){//授权成功
			$resurl = F($state);
			 $map['openid'] = array('like',$token_openid['openid']);
			 $mo = D("UserWx");
			 $info = $mo->join('LEFT JOIN __USER_WX_SK__ ON __USER_WX__.user_base = __USER_WX_SK__.user_id')->where($map)->find();
//			 dump($info);
			 session('wxopenid',$token_openid['openid']);
			 $ticket = session('wxopenid-ticket');
			 if(!$ticket){
				 $ticket = createNonceStr();
				 session('wxopenid-ticket',$ticket);
			 }
			 if($info==FALSE){
			 	$token_openid['user_base'] = 'wx'.$wxid.'-'.$token_openid['openid'];
			 	$token_openid['userid'] = $mo->uuid();
			 	$ret = $mo->add($token_openid);
				//-----额外增加的字段
				$token_openid['access_ticket'] = $ticket;
				$token_openid['login_time'] = hmtime() ;
				$token_openid['user_agent'] = I("server.HTTP_USER_AGENT");
				$token_openid['ip'] = get_client_ip();
				$token_openid['id'] = $token_openid['userid'];
				$token_openid['username'] = $token_openid['openid'];
				D("UserBase")->add(array('userid'=>$token_openid['user_base']));// 主表
				if($ret!=FALSE){
					$redis = A('Redis','Event');
					$redis->setUserInfo($token_openid);
				}
			 }else{
			 		$redis = A('Redis','Event');
					//------额外增加的字段
					$info['access_ticket'] = $ticket;
					$info['login_time'] = hmtime() ;
					$info['user_agent'] = I("server.HTTP_USER_AGENT");
					$info['ip'] = get_client_ip();
					$info['id'] = $info['user_base'];
					$info['username'] = $info['openid'];
//					dump($info);
					$redis->setUserInfo($info);
					$token_openid = $info;
			 }
			 //商客 和 普通用户 建立联系
			 $skE = A("Sk",'Event');
			 $art = convertUrlQuery($resurl);
			 $skE->bingWxUserSk($token_openid['user_base'],$art['companycode'],$art['logincode']);
			 cookie('juyou-ticket',$token_openid['access_ticket']);
		}else{
			$resurl = F($state).'&uuid=error&errcode='.$res['errcode'];
		}
		
			//dump($token_openid);
		
			redirect($resurl); 
	}


	/*
	 * 网页授权-给用户领取 积分用的
	 */
	public function authBasejf(){
		$all = I('get.');
		$wxid = I('wxid');
		$rurl = '';
		foreach ($all as $key => $value) {
			if($key=='rurl'){
				$rurl = $value;
			}else{
				$rurl = $rurl.'&'.$key.'='.$value;
			}
		}
		$ts = time().rand(1000, 9999);
		F($ts,$rurl);
		$myurl = C('HD_BASE_URL').U('requestUrljf',array('wxid'=>$wxid)); 
		$wechatObj = A('WxChat','Logic');
		$res = $wechatObj->getcodeUrl($ts,$myurl,$wxid);
		redirect($res);
	}
	/*
	 * 积分授权  回调
	 */
	
	public function requestUrljf(){
		$code = I('code');
		$wxid = I('wxid');
		$state = I('state');
		$resurl = '';
		$wechatObj = A('WxChat','Logic');
		$token_openid = $wechatObj->getWyInfo($code,$wxid);
		$res = $wechatObj->WxError($token_openid);
		if($res==FALSE){//授权成功
			$resurl = F($state);
			 $map['openid'] = array('like',$token_openid['openid']);
			 $mo = D("UserWx");
			 $info = $mo->where($map)->find();
			 session('wxopenid',$token_openid['openid']);
			 
			 if($info==FALSE){
			 	$token_openid['user_base'] = 'wx'.$wxid.'-'.$token_openid['openid'];
			 	$token_openid['userid'] = $mo->uuid();
			 	$ret = $mo->add($token_openid);
				D("UserBase")->add(array('userid'=>$token_openid['user_base']));// 主表
			 }else{
			 	$token_openid['userid'] = $info['userid'];
			 }
			if(strpos($resurl,'?')!=FALSE){
					$resurl = $resurl.'&uuid='.$token_openid['userid'];
				}else{
					$resurl = $resurl.'?uuid='.$token_openid['userid'];
				}
		}else{
			$resurl = F($state).'&uuid=error&errcode='.$res['errcode'];
		}
		
			redirect($resurl); 
	}
	
	
	/*
	 * 积攒-积分
	 */
	 public function dcredits(){
	 	$credit = A('Sk','Event');
		$credit->credits();
	 }
	 
	 /*
	  * 积分列表 
	  */ 
	public function creditsList(){
		$credit = A('Sk','Event');
		$credit->creditsList();
	}
	
	/*
	 * 引入微信sdk js 首先调用此接口
	 */
	public function jsSdkinit($wxid){
		$wechatObj = A('WxChat','Logic');
		$wechatObj->getJsAll($wxid);
	}
	
	/*
	 * 商客支付-签名数据接口
	 */
	public function skJsPay(){
		$sk = A('Sk','Event');
		$sk->skPay(); 
	}

	/*
	 * 微信通用支付接口
	 */
	public function jsPay(){
		$sk = A('Common','Event');
		$sk->GzhPay(); 
	}
	
	
	/*
	 * 商客支付成功-回调函数-处理订单确认
	 */
	public function skNotify(){
		$wxid = I('post.wxid');
		$notifyurl = U('Api/skNotify',array('wxid'=>$wxid),'',TRUE);
		
		$notify = A('WxNotify','Logic');
		$notify->Notify($wxid,$notifyurl);
	}
	
	/*
	 * 通用支付 回调函数
	 */
	public function CommonNotify(){
		$wxid = I('post.wxid');
		$notifyurl = U('Api/CommonNotify',array('wxid'=>$wxid),'',TRUE);
		
		$notify = A('WxNotify','Logic');
		$notify->CommonNotify($wxid,$notifyurl);
	}
	
	/*
	 * 对外公众号消息通知接口
	 */
	public function sendNotice(){
		$notice = A('WxNotice','Logic');
		$info = array('1','2','3','4');
		$notice->wxSendNotice(1,$info,'test','notice100');
	}
	
	
	/*
	 * 我的信息 详情
	 */
	public function myInfo(){
		$openid =  session('wxopenid');
		if($openid){
			$map['openid'] = $openid;
			$info = D("UserWx")->where($map)->find();
			if($info==FALSE)jsonReturn(110,'获取信息失败');
			jsonReturn(1,'获取信息成功',$info);
		}else{
			jsonReturn(110,'你没有登录');
		}
	}
	
	public function tt(){
		//echo U('justBaseAouthMore',array('id'=>I('wxid')),'',TRUE);
		//dump( D('UserWx')->select());
		//dump(file_get_contents('http://www.baidu.com'));			
		
//		$use = A("Redis",'Event');
//		$info = array('username'=>'mike1','tel'=>'15040249808','user_base'=>'12345678');
//		$use->setUserInfo($info);
//		dump($use->getUserInfo('12345678'));
		//微信支付测试
//		$wxpay = A('WxPay',"Logic");
//		//$wxpay = new \Api\Logic\WxChatLogic
//		$json = $wxpay->gzhPay(1);
//		 $this->assign('json',$json);
//		$this->display();

//
//				$mps['login_name'] = 'LA00044wangnan';
//				$user = D("SysUser")->where($mps)->find();
//				echo 's21s';
//				dump($user);
//				$mps['code'] = 'LA00044';
//				$rest = D('SysUser')->join('JOIN __SYS_OFFICE__ ON __SYS_USER__.office_id = __SYS_OFFICE__.id')->where($mps)->field('code')->select(false);
//				dump($rest);
//				
				//商客和 用户绑定 测试
//				 $skE = A("Sk",'Event');
//			     $skE->bingWxUserSk('222222');
//              $url = "http://www.baidu.com?abc=123&user=mike&mobile=15040249808";
//				dump(convertUrlQuery($url));
//				echo '222224442249999</br>';
//				echo I("server.HTTP_USER_AGENT");
//				dump(I('server.'));

//				$map['openid'] = array('like','oaugowRfzIQDRqk0T4GCi3F1LxzE');
//				 $mo = D("UserWx");
//				 $info = $mo->join('LEFT JOIN __USER_WX_SK__ ON __USER_WX__.user_base = __USER_WX_SK__.user_id')->where($map)->find();
//				echo '22';
//				trace($info);	

			$sk = array("amount"=>"1.50",
							"channel"=>"wx",
							"order_no"=>"S101482215039803",
							"buyer_id"=>"oaugowRfzIQDRqk0T4GCi3F1LxzE",
							"trade_state"=>"success",
							"transaction_id"=>"4006962001201612203383238040",
							"time_end"=>"2016-12-20 14:24:10",
							"notify_time"=>"2016-12-20 14:25:10",
							"notify_type"=>"trade",
							"extra_param"=>"1");
			dump($sk);
			$ret = D("SysWxchatCallbackLog")->add($sk);
			dump($ret);
		
	}
}
