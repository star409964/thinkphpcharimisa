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
use Api\Logic\WxChatBaseLogic;
class WxChatLogic extends WxChatBaseLogic {

	//公众号添加url的时候 认证需要调用此函数
	public function valid() {
		$accountList = $this -> getAccountList();
		$id = $_GET['id'];
		if (I('signature') && I('timestamp') && I('nonce')) { 
			$res = $this -> checkSignature(I('signature'), I('timestamp'), I('nonce'), $accountList[$id]['token' ]);
				if($res){
					echo I('echostr');
					SetLog('验证的id='.$id.'验证的字符串='.I('echostr'), 'valid.log');
					exit;
				}
			} else {
				//SetLog('测试当前的id='.$id, 'valid.log');
				$this -> acceptMsg($id);
			}
		}
	//认证使用
	private function checkSignature($signature, $timestamp, $nonce, $token) {
		$tmpArr = array($token, $timestamp, $nonce);
		// use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr); 
		if ($tmpStr == $signature) { 
			return true;
		} else {
			return false;  
		}
	}

	/*
	 * 处理微信服务器推送过来的消息
	 */
	private function acceptMsg($wxid) {
		$msg = A('WxChatMsg','Logic');
		$msg->acceptMsg($wxid);
	}
	
	
	//---------------------网页获取信息－－－－－－－－－－－－
	 /**
	 * 函数说明：跳转到授权页面
	 * @deprecated 创建时间：2016-1-29
	 * @deprecated 备注：$rurl 前台要跳转的url，$myurl跳转到我自己服务器的url
	 * @author mike<stardandan@126.com>
	 * @param string $id
	 * @return object
	 */
	public function getcodeUrl($rurl,$myurl,$wxid){
		if(empty($myurl)){
			$myurl = urlencode('http://sylyx.cn/index.php/Wx/getInfoAouth');//只要是授权域名之下的地址就可以
		}else{
			$myurl = urlencode($myurl)	;
		}
		$appId = self::$accountList[$wxid]['appid'];
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".$myurl."&response_type=code&scope=snsapi_userinfo&state=".$rurl."#wechat_redirect";
		return $url;
	}
	/*
	 * 静默授权 
	 */
	
	public function getBasecodeUrl($rurl,$myurl,$tokenid){
		if(empty($myurl)){
			$myurl = urlencode('http://sylyx.cn/index.php/Wx/getBaseAouth');//只要是授权域名之下的地址就可以
		}else{
			$myurl = urlencode($myurl)	;
		}
		$appId = self::$accountList[$tokenid]['appid'];
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".$myurl."&response_type=code&scope=snsapi_base&state=".$rurl."#wechat_redirect";
		return $url;
	}
	 /**
	 * 函数说明：根据code 获取用户的openid和token[id哪个公众号的]
	 * @deprecated 创建时间：2016-1-29
	 * @deprecated 备注：
	 * @author mike<stardandan@126.com>
	 * @param string $code
	 * @return object
	 */
	public function getTokenANDopenid($code,$id){
		if(empty($code) && empty($id)){
			$res = FALSE;
		}else{
			$res = $this->getWyAccessToken($id,$code);
		}
		$this->setE('getTokenANDopenid',ReturnWxError($res));
		return $res;
	}
	/**
	 * 函数说明：根据code 获取用户的所有信息
	 * @deprecated 创建时间：2016-12-10
	 * @deprecated 备注：
	 * @author mike<stardandan@126.com>
	 * @param string $code
	 * @return object
	 */
	public function getWyInfo($code,$id){
		$accid = $this->getTokenANDopenid($code,$id);
		$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$accid['access_token'].'&openid='.$accid['openid'].'&lang=zh_CN';
		$res = json_decode(httpGet($url),TRUE);	
		$this->setE('getWyInfo', ReturnWxError($res));
		return $res;
	
	}
	
	
	
	/**
	 * 函数说明：验证token是否有效
	 * @deprecated 创建时间：2016-1-29
	 * @deprecated 备注：errcode   为0成功
	 * @author mike<stardandan@126.com>
	 * @param string $code
	 * @return array
	 */
	public function verifyToken(){
		$accid = $this->getTokenANDopenid($code);
		$url = 'https://api.weixin.qq.com/sns/auth?access_token='.$accid->access_token.'&openid='.$accid->openid;
		$res = json_decode(httpGet($url),TRUE);	
		$this->setE('verifyToken', $res);
		return $res;
	}
	
	/*
	 * 自定义调用微信返回的错误代码日志
	 */
	public function setE($funs,$errcode){
		if($errcode){
			SetLog('函数['.$funs.']'.'错误代码【'.$errcode.'】', 'setE.log');
		}
	}
	
	/**
	 * 函数说明：微信返回错误代码处理规则
	 * @deprecated 创建时间：2016-12-10
	 * @deprecated 备注：没有错误返回false，$k 为错误数组
	 * @author mike<stardandan@126.com>
	 * @param array $k
	 * @return boolern
	 */
	 public function WxError($k){
		 if(!is_array($k)){
		 	$k = json_decode($k);
		 } 
	 	if(isset($k['errcode'])){ 
			$res = ReturnWxError($k['errcode']);
		 	$res = array('errcode'=>$k['errcode'],'msg'=>$res);
		}else{ 
	 		$res = False;
	 	}
		return $res;
	 	}
	
		
	/*
	 * 调用微信js初始化 参数
	 */
	public function getJsAll($wxid){
		$appid = self::$accountList[$wxid]['appid'];
		$appSecret =  self::$accountList[$wxid]['appsecret'];
		$url = $_REQUEST['signurl'];
		$rest = $this->getSignPackage($url,$wxid);
		$list = array_slice($rest,0,5);
		jsonReturn(1,'SUCCESS',$list);
	}
	/*
	 * js签名验证
	 */
	 public function getSignPackage($url,$wxid) {
	    $jsapiTicket = $this->getJsApiTicket($wxid);
	    $timestamp = time();
	    $nonceStr = createNonceStr();

	    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
	    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

   		 $signature = sha1($string);

	    $signPackage = array(
	      "appId"     => self::$accountList[$wxid]['appid'],
	      "nonceStr"  => $nonceStr,
	      "timestamp" => $timestamp,
	      "url"       => $url,
	      "signature" => $signature,
	      "rawString" => $string,
	      "url" =>$url
	    );
	    return $signPackage; 
  }
	

  private function getJsApiTicket($wxid) {
	$cache = S(array('type'=>'file','prefix'=>'jssdk','expire'=>7200));
    if (!$cache->name) {
      $accessToken = $this->getAccessToken($wxid);
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode(httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $cache->name = $ticket;
      }
    } else {
      $ticket = $cache->name; 
    }
    return $ticket;
  }
  
	/**
	 * 函数说明：一键设置菜单
	 * @deprecated 创建时间：2016-12-19
	 * @deprecated 备注：errcode   为0成功
	 * @author mike<stardandan@126.com>
	 * @param string $code
	 * @return array
	 */
	public function setMenu($button,$wxid){
		$token = $this->getAccessToken($wxid);
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$token;
		$res = json_decode(httpPost($url,$button),TRUE); 
		if($res['errcode']!=0){//异常处理
		 	jsonReturn('110',ReturnWxError($res).$res['errmsg']);
		 }else{
		 	jsonReturn(1,'菜单设置成功');
		 }
	}
	
	/**
	 * 函数说明：获取临时素材---传递到oss上
	 * @deprecated 创建时间：2016-12-19
	 * @author mike<stardandan@126.com>
	 * @param string $media_id【素材的id】
	 * @param string $wxid【公众号的id】
	 * @return json
	 */
	public function getMedias($media_id,$wxid){
		$token = $this->getAccessToken($wxid);
		$url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$media_id;
		$res = httpGet($url); 
		$oss = A('AliOss','Logic');
	    $oss->putObject('wx/'.$media_id.'.jpg',$res);
		jsonReturn(1,'文件传递成功',C('OSS_BASE_URL').'wx/'.$media_id.'.jpg');
		
	}
	/**
	 * 函数说明：获取微信的永久图片素材---传递到oss上
	 * @deprecated 创建时间：2016-12-19
	 * @author mike<stardandan@126.com>
	 * @param string $media_id【素材的id】
	 * @param string $wxid【公众号的id】
	 * @return json
	 */
	public function getMediasImage($media_id,$wxid){
		$token = $this->getAccessToken($wxid);
		$url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.$token;
		$res = httpPost($url, json_encode(array('media_id'=>$media_id),JSON_UNESCAPED_UNICODE)); 
		//$oss = A('AliOss','Logic');
		$oss = new \Api\Logic\AliOssLogic();
	    $oss->putObject('wxmedia/'.$media_id.'.jpg',$res);
		return 'wxmedia/'.$media_id.'.jpg';
	}
	
	 /**
	 * 函数说明：获取永久素材列表
	 * @deprecated 创建时间：2016-12-24
	 * @deprecated 备注：素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
	 * @author mike<stardandan@126.com>
	 * @param string $wxid【公众号的id】
	 * @return array
	 */
	   public function getMaterials($wxid,$type='news',$offset=0,$count=20){
	 	$token = $this->getAccessToken($wxid);
		$url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$token;
		$srt = '{"type": "'.$type.'", "offset":"'.$offset.'","count":"'.$count.'"}';
		$res = json_decode(httpPost($url,$srt),TRUE); 
		return $res;
	 }
	
}//class end
