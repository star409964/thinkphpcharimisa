<?php
/**
 * lyx
 * ============================================================================
 * 版权所有 2016-2030 沈阳隆源兴网络科技有限公司，并保留所有权利。
 * 网站地址: http://sylyx.cn
 * ----------------------------------------------------------------------------
 * 开源软件 可以用到个人与商业用途.
 * ============================================================================
 * Author: mike<stardandan@126.com>
 * Date: 2016-11-21
 */

/**
 * 获取节点的分组名字
 * @param $id 分组的主键
 */
function getNodeGroupName($id) {
	if ($id==0) {
		return '未分组';
	}
	 if (isset ( $_SESSION ['nodeGroupList'] )) {
		return $_SESSION ['nodeGroupList'] [$id];
	} 
	$Group = D ( "Group" );
	$list = $Group->getField ( 'id,title' );
	$_SESSION ['nodeGroupList'] = $list;
	$name = $list [$id];
	return $name;
}
/**
 * 状态显示
 * @param $status 状态值
 * @param $id 数据库主键
 */
function showStatus($status, $id) {
	switch ($status) {
		case 0 :
			$info = '<a href="'.__CONTROLLER__.'/resume/id/' . $id .'" class="lyx_status" ><i class="glyphicon glyphicon-remove"></i></a>'; //恢复
			break;
		case 2 :
			$info = '<a href="'.__CONTROLLER__.'/pass/id/' . $id .'" class="lyx_status"><i class="icon-check"></i></a>';//批准
			break;
		case 1 :
			$info = '<a href="'.__CONTROLLER__.'/forbid/id/' . $id .'" class="lyx_status"><i class="glyphicon glyphicon-ok"></i></a>';//禁用
			break;
		case - 1 :
			$info = '<a href="'.__CONTROLLER__.'/recycle/id/' . $id .'" class="lyx_status"><i class=" icon-share"></i></a>';//还原
			break;
	}
	return $info;
}
/**
 * 获取上一节点
 * @param $pid 当前节点
 */
function getParentNodeId($pid){
	if(!empty($pid)){
	$id = D('Node')->where('id='.$pid)->getField('pid');}
	return $id?$id:0;
}

function pwdHash($password, $type = 'md5') {
	return hash ( $type, $password );
}
/*
 * 获取 图片地址
 */
function getMediaImagUrl($media_id_myurl){
	return C("OSS_BASE_URL").$media_id_myurl;
}

/*
 * 回复规则-类型
 */
function getRuleKeywordType($type){
	switch ($type) {
		case '1':
			$text = '匹配';
			break;
		case '2':
			$text = '包含';
			break;
		case '3':
			$text = '正则表达式';
			break;	
		default:
			$text = '没有此规则';
			break;
	}
	return $text;
}

/*
 * 回复规则-对应的模块
 */
function getRuleKeywordModel($type){
	switch ($type) {
		case 'news':
			$text = '多图文回复';
			break;
		case 'basic':
			$text = '文本回复';
			break;
		case 'images':
			$text = '图片回复';
			break;	
		case 'voice':
			$text = '语音回复';
			break;	
		case 'video':
			$text = '视频回复';
			break;
		case 'music':
			$text = '音乐回复';
			break;				
		default:
			$text = '没有此规则';
			break;
	}
	return $text;
}
