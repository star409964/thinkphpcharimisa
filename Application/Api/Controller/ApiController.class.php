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
			 $info = $mo->where($map)->find();
			 session('wxopenid',$token_openid['openid']);
			 if($info==FALSE){
			 	$token_openid['user_base'] = 'wx'.$wxid.'-'.$token_openid['openid'];
			 	$token_openid['userid'] = $mo->uuid();
			 	$ret = $mo->add($token_openid);
				D("UserBase")->add(array('userid'=>$token_openid['user_base']));// 主表
				if($ret!=FALSE){
					$redis = A('Redis','Event');
					$redis->setUserInfo($token_openid);
				}
			 }else{
			 		$redis = A('Redis','Event');
					$redis->setUserInfo($info);
					$token_openid['user_base'] = $info['user_base'];
			 }
			 
			if(strpos($resurl,'?')!=FALSE){
					$resurl = $resurl.'&uuid='.$token_openid['user_base'];
				}else{
					$resurl = $resurl.'?uuid='.$token_openid['user_base'];
				}	
		}else{
			$resurl = F($state).'&uuid=error&errcode='.$res['errcode'];
		}
			redirect($resurl); 
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
	 * 商客支付成功-回调函数-处理订单确认
	 */
	public function skNotify(){
		$wxid = I('post.wxid');
		$notifyurl = U('Api/skNotify',array('wxid'=>$wxid),'',TRUE);
		
		$notify = A('WxNotify','Logic');
		$notify->Notify($wxid,$notifyurl);
	}
	
	/*
	 * 对外公众号消息通知接口
	 */
	public function sendNotice(){
		$notice = A('WxNotice','Logic');
		$info = array('1','2','3','4');
		$notice->wxSendNotice(1,$info,'test','notice100');
	}
	
	public function tt(){
		//echo U('justBaseAouthMore',array('id'=>I('wxid')),'',TRUE);
		//dump( D('UserWx')->select());
		//dump(file_get_contents('http://www.baidu.com'));			
		
//		$use = A("Redis",'Event');
//		$info = array('username'=>'mike1','tel'=>'15040249808','user_base'=>'12345678');
//		$use->setUserInfo($info);
//		dump($use->getUserInfo('12345678'));

		$wxpay = A('WxPay',"Logic");
		//$wxpay = new \Api\Logic\WxChatLogic
		$json = $wxpay->gzhPay(1);
		 $this->assign('json',$json);
		$this->display();
		
		
	}
}
