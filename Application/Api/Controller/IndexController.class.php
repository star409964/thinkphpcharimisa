<?php
/**
 * lyx
 * ============================================================================
 * 版权所有 2016-2030 沈阳隆源兴网络科技有限公司，并保留所有权利。
 * 网站地址: http://sylyx.cn
 * ----------------------------------------------------------------------------
 * index默认为测试控制器，和接口出口控制器
 * ============================================================================
 * Author: mike<stardandan@126.com>
 * Date: 2016-11-21
 */
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
	
	public function index(){
		echo 'ss';
	}
	
	public function upload() {
		$upload = new \Think\Upload();
		// 实例化上传类
		$upload -> maxSize = 3145728;
		// 设置附件上传大小
		$upload -> exts = array('jpg', 'gif', 'png', 'jpeg');
		// 设置附件上传类型
		$upload -> rootPath = './Uploads/';
		// 设置附件上传根目录
		$upload -> savePath = '';
		// 设置附件上传（子）目录
		// 上传文件
		$info = $upload -> upload();
		if (!$info) {// 上传错误提示错误信息
			jsonReturn(999,$upload -> getError());
		} else {// 上传成功
			jsonReturn(1,'上传成功！');
		}
	}
	/*微信小程序解密
	 * 2016-11-28
	 * sessionKey 签名校验算法涉及用户的session_key
	 * encryptedData 要解析的数据
	 * iv 对称解密算法初始向量 iv 
	 */
	public function wxXcxDecode($sessionKey,$encryptedData,$iv){
		Vendor("WxXcxDecode.wxBizDataCrypt");
		$appid = 'wxa29982fcd9fc94d8';
		$pc = new \WXBizDataCrypt($appid, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $data );
		if ($errCode == 0) {
		    jsonReturn(1,'解析成功',$data);
		} else {
			jsonReturn(999,'errorCode='.$errCode);
		}
	}
}
