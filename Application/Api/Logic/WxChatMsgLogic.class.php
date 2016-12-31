<?php
/**
 * 微信服务器 发送来的消息整体处理 逻辑
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
class WxChatMsgLogic extends WxChatBaseLogic {


	/*
	 * 处理微信服务器推送过来的消息
	 */
	public function acceptMsg($wxid) {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
      	//extract post data
		if (!empty($postStr)){
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
				$type = $postObj->MsgType;
				
              		switch ($type) {
              			case 'text': $this->manageText($postObj,$wxid);
              				break;
						case 'image':$this->manageImage($postObj,$wxid);
              				break;
						case 'voice':$this->manageVoice($postObj,$wxid);
              				break;
						case 'video':$this->manageVideo($postObj,$wxid);
              				break;
						case 'shortvideo':$this->manageShortvideo($postObj,$wxid);
              				break;
						case 'location':$this->manageLocation($postObj,$wxid);
              				break;
						case 'link':$this->manageLink($postObj,$wxid);
              				break;						
              			case 'event':$this->manageEvent($postObj,$wxid);
              				break;
              			default:$this->manageDefault($postObj,$wxid);
              				break;
              		}				
        }else {
        	exit;
        }
	}
	
	/*
	 * 处理 文字请求
	 * 【匹配的优先级[1]>包含的优先级[2]>正则表达式的优先级[3]】
	 */
	private function manageText($obj,$wxid){
		$keyword = trim($obj->Content);
		$keyword_list = M("RuleKeyword")->where("wxid=".$wxid)->order('displayorder')->select();
		$now_info = FALSE;
		foreach ($keyword_list as $key => $value) {
			if(stristr($keyword,$value['content'])!=FALSE){
				if($value['type']==1){//匹配
					$now_info = $value;
					break;
				}else{
					$now_info = $value;
					break;
				}
			}
		}
		if($now_info!=FALSE){
			switch ($now_info['module']) {
				case 'news':
					$map['wxid'] = $wxid;
					$map['media_id'] = $now_info['media_id'];
					$list = M('MediaNews')->where($map)->select();
					$this->replyNews($obj, $list);
					break;
				case 'basic':
					$map['wxid'] = $wxid;
					$map['media_id'] = $now_info['media_id'];
					$list = M('MediaBasic')->where($map)->find();
					$this->replyText($obj, $list['content']);
					break;
				case 'images':
					break;
				case 'music':
					break;
				case 'video':
					break;
				case 'voice':
					break;				
				default:
					break;
			}
		}else{
			$this->manageDefault($obj,'没有匹配关键字');
		}
	}
	
	/*
	 * 处理 图片请求
	 */
	private function manageImage($obj,$wxid){
		$this->replyImage($obj,$obj->MediaId);
	}
	
	/*
	 * 处理 语音请求
	 */
	private function manageVoice($obj,$wxid){
		$this->manageDefault($obj);
	}
	
	/*
	 * 处理 视频请求
	 */
	private function manageVideo($obj,$wxid){
		$this->manageDefault($obj);
	}
	
	/*
	 * 处理 小视频请求
	 */
	private function manageShortvideo($obj,$wxid){
		$this->manageDefault($obj);
	}
	
	/*
	 * 处理 位置请求
	 */
	private function manageLocation($obj,$wxid){
		$this->manageDefault($obj);
	}
	
	/*
	 * 处理 链接请求
	 */
	private function manageLink($obj,$wxid){
		$this->manageDefault($obj);
	}
	
	/*
	 * 处理 事件请求
	 */
	private function manageEvent($obj,$wxid){
		$type = $obj->Event;
//		$this->manageDefault($obj,'不能识别的事件'.$type);
		switch ($type) {
			case 'LOCATION'://上报地理位置
						$this->manageEventLocation($obj,$wxid);
				break;
			case 'subscribe'://第一次关注
						$this->manageEventSubscribe($obj,$wxid);
				break;
			case 'CLICK'://点击菜单事件
						$this->manageEventClick($obj,$wxid);
				break;
			case 'VIEW'://点击菜单 跳转链接的时候
						$this->manageEventView($obj,$wxid);
				break;
			default:$this->manageDefault($obj,'不能识别的事件');
				break;
		}
	}
	
	/*
	 * 处理 其他请求
	 */
	private function manageDefault($obj,$str=null){
		$type = $obj->MsgType;
		$event = $obj->Event;
        $keyword = trim($obj->Content);
		if($str==null){
			$contentStr = $type.'-----'.$event;
		}else{
			$contentStr = $str;
		}
		$this->replyText($obj,$contentStr);				
	}
	
	/*
	 * 处理 事件-上报地址位置-请求
	 */
	private function manageEventLocation($obj,$wxid){
		$data = self::xmlToArrayElement($obj);
		$ret = M('WxuserLocationLog')->add($data);
		//$this->manageDefault($obj,$ret!=FALSE ? '成功':'失败');
	}
	
	/*
	 * 处理 事件-第一次关注-请求
	 */
	private function manageEventSubscribe($obj,$wxid){
		$this->manageDefault($obj,'欢迎关注商客');
	}
	
	/*
	 * 处理 事件-点击菜单事件-请求
	 */
	private function manageEventClick($obj,$wxid){
		$this->manageDefault($obj,'点击菜单');
	}
	
	/*
	 * 处理 事件-点击菜单跳转链接-请求
	 */
	private function manageEventView($obj,$wxid){
		$this->manageDefault($obj,'你点击了'.$obj->EventKey);
	}
	
	
	/**
     * xml文档转为数组元素
     * @param obj $xmlobject XML文档对象
     * @return array
     */
    public static function xmlToArrayElement($xmlobject) {
        $data = array();
        foreach ((array) $xmlobject as $key => $value) {
            $data[$key] = !is_string($value) ? self::xmlToArrayElement($value) : $value;
        }
        return $data;
    }
	
	/*
	 * 回复文本消息
	 * [消息来源对象][要回复的文本内容]
	 */
	 private function replyText($obj,$text){
	 	$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>"; 
		$resultStr = sprintf($textTpl,$obj->FromUserName,$obj->ToUserName, time(),'text', $text);
        echo $resultStr;					
	 }
	 
	 /*
	 * 回复图片消息
	 * [消息来源对象][要回复图片素材的media_id]
	 */
	 private function replyImage($obj,$media_id){
	 	$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Image>
					<MediaId><![CDATA[%s]]></MediaId>
					</Image>
					</xml>"; 
		$resultStr = sprintf($textTpl,$obj->FromUserName,$obj->ToUserName, time(),'image',$media_id);
        echo $resultStr;					
	 }
	 
	  /*
	 * 回复语音消息
	 * [消息来源对象][要回复语音素材的media_id]
	 */
	 private function replyVoice($obj,$media_id){
	 	$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Voice>
					<MediaId><![CDATA[%s]]></MediaId>
					</Voice>
					</xml>"; 
		$resultStr = sprintf($textTpl,$obj->FromUserName,$obj->ToUserName, time(),'voice',$media_id);
        echo $resultStr;					
	 }
	 
	 /*
	 * 回复视频消息
	 * [消息来源对象][要回复语音素材的media_id][视频标题][视频描述]
	 */
	 private function replyVideo($obj,$media_id,$title,$des){
	 	$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Video>
					<MediaId><![CDATA[%s]]></MediaId>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					</Video> 
					</xml>"; 
		$resultStr = sprintf($textTpl,$obj->FromUserName,$obj->ToUserName, time(),'video',$media_id,$title,$des);
        echo $resultStr;					
	 }
	 
	 /*
	 * 回复音乐消息
	 * [消息来源对象][要回复语音素材的media_id][音乐标题][音乐描述][音乐的url][高音质的url][缩略图的media_id]
	 */
	 private function replyMusic($obj,$media_id,$title,$des,$MusicURL,$HQMusicUrl,$media_id){
	 	$textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Music>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					<MusicUrl><![CDATA[%s]]></MusicUrl>
					<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
					<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
					</Music> 
					</xml>"; 
		$resultStr = sprintf($textTpl,$obj->FromUserName,$obj->ToUserName, time(),'music',$title,$des,$MusicURL,$HQMusicUrl,$media_id);
        echo $resultStr;					
	 }
	 
	 /*
	 * 回复图文消息
	 * [消息来源对象][要回复图文的数组]
	 */
	 private function replyNews($obj,$list){
	 	 	$num = count($list);	
			$msg='<xml>
				<ToUserName><![CDATA['.$obj->FromUserName.']]></ToUserName>
				<FromUserName><![CDATA['.$obj->ToUserName.']]></FromUserName>
				<CreateTime>'.time().'</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				';
			$msg2 = '';
			$msg3='</Articles></xml>';
			if($list!=FALSE){
				foreach ($list as $key =>$value) {
				$sr='<item>
					<Title><![CDATA['.$value["title"].']]></Title> 
					<Description><![CDATA['.$value["digest"].']]></Description>
					<PicUrl><![CDATA['.$value["thumb_url"].']]></PicUrl>
					<Url><![CDATA['.$value["url"].']]></Url>
					</item>
					';
				$msg2 = $msg2.$sr;	
				}
			}
			$msg1 ='<ArticleCount>'.$num.'</ArticleCount>
					<Articles>
					';
			$res = $msg.$msg1.$msg2.$msg3;
       		 echo $res;					
	 }
	 
	 
}//class end
