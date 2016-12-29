<?php
/**
 * 获取微信服务器 素材到本地 事件
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
class MediaEvent {
	/**
	 * 函数说明：获取微信服务器素材到 本地
	 * @deprecated 创建时间：2016-12-25
	 * @deprecated 备注：errcode   为0成功
	 * @author mike<stardandan@126.com>
	 * @param string $type【获取素材类型】
	 * @param string $offset【结束的id值】
	 * @return json
	 */
	public function getmedia($wxid,$type,$offset = 0) {
		$ts = $type;
		switch ($ts) {
			case '1' :
				$type = 'news';
				$mo = M('MediaNews');
				break;
			case '2' :
				$type = 'image';
				$mo = M('MediaImages');
				break;
			case '3' :
				$type = 'video';
				$mo = M('MediaVideo');
				break;
			case '4' :
				$type = 'voice';
				$mo = M('MediaVoice');
				break;
			case '5' :
				$type = 'text';
				$mo = M('MediaBase');
				break;
			case '6' :
				$type = 'music';
				$mo = M('MediaMusic');
				break;
//			default :
//				$type = 'news';
//				$mo = M('MediaNews');
//				break;
		}
		$wxchatObj = new \Api\Logic\WxChatLogic();
		$count = 20;
		$res = $wxchatObj->getMaterials($wxid,$type, $offset, $count);
		dump($res);
		$total_count = $res['total_count'];
		//素材总数
		$item_count = $res['item_count'];
		//本次获取的数量
		if ($total_count <= 20) {//没有其他素材-素材总数
			//$res = $wechatObj->getMaterials($type,$offset,$count);
			$item_count = $res['item_count'];
			if ($ts == 1) {
				$this -> insertMediaNews($wxid, $res['item']);
			} else {
				$this -> insertMediaOther($wxid,$res['item'],$mo);
			}

		} else {//还有素材  接着执行
			while ($item_count <= 20 && $item_count > 0) {
				if ($ts == 1) {
					$this -> insertMediaNews($wxid, $res['item']);
				} else {
					$this -> insertMediaOther($wxid,$res['item'],$mo);
				}
				$offset = $offset + $item_count;
				$res = $wechatObj -> getMaterials($type, $offset, $count);
				$item_count = $res['item_count'];
			}

		}
	}

	//插入多图文到数据库
	private function insertMediaNews($wxid, $data) {

		$list = D('MediaNews') -> getField('media_id', TRUE);
		trace('打印数据库里面的media_id=',json_encode($list));
		foreach ($data as $key => $value) {
			$mike['media_id'] = $value['media_id'];
			$mike['time'] = $value['update_time'];
			// 一个组的更新时间
			$nums = 0;
			$mike['create_time'] = $value['content']['create_time'];
			$mike['update_time'] = $value['content']['update_time'];
			$wxchat = new \Api\Logic\WxChatLogic();
			if (!in_array($value['media_id'], $list)) { trace('判断media_id是否已经存入数据库');
				if (count($value['content']['news_item']) > 0) {
					foreach ($value['content']['news_item'] as $keys => $val) {
						$mike['title'] = $val['title'];
						$mike['author'] = $val['author'];
						$mike['digest'] = $val['digest'];
						$mike['content'] = $val['content'];
						$mike['content_source_url'] = $val['content_source_url'];
						$mike['thumb_media_id'] = $val['thumb_media_id'];
						$mike['show_cover_pic'] = $val['show_cover_pic'];
						$mike['url'] = $val['url'];
						$mike['thumb_url'] = $val['thumb_url'];
						$mike['credits'] = 10;
						$mike['color'] = $key;
						$mike['wxid'] = $wxid;
						$mike['thumb_myurl'] = $wxchat->getMediasImage($val['thumb_media_id'],$wxid);
						$datas[] = $mike;
					}
				}
			}else{
				echo '已经存在';
			}
		}
		if(!empty($datas)){
			$resk = M('MediaNews') -> addAll($datas);
			if ($resk != FALSE) {
				echo '插入成功-=</br>';
			} else {
				echo '插入失败false-=</br>';
			}
		}
		
	}
	
	//插入多图文到数据库
	private function insertMediaOther($wxid, $data,$mo) {
		$wxchat = new \Api\Logic\WxChatLogic();
		$list = $mo -> getField('media_id', TRUE);
			foreach ($data as $key => $value) {
					if (!in_array($value['media_id'], $list)) { 
						$data[$key]['myurl'] = $wxchat->getMediasImage($value['media_id'],$wxid);
						$datas = $data[$key];
					}
		}
			if(!empty($datas)){
				$resk = $mo->addAll($datas);
				if ($resk != FALSE) {
						echo '插入成功-=</br>';
					} else {
						echo '插入失败false-=</br>';
					}
			}
	}
	
	
	
	
	
	public function tt() {
		echo U('Api/skNotify', array('wxid' => 1), '', TRUE);
	}

}
