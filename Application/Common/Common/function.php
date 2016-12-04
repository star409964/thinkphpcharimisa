<?php
/*返回固定的json数据
 * 2016-11-28
 */
function jsonReturn($status=1,$msg='',$data=''){
    if(empty($data))
        $data = '';
    $info['status'] = $status;
    $info['msg'] = $msg;
    $info['result'] = $data;
    exit(json_encode($info,JSON_UNESCAPED_UNICODE));
	//$this->ajaxReturn($info);
}
	/**
	 * 获取微信的appid标识
	 * @return string
	 */
function getAppid(){
	return '';
}
	/**
	 * 获取微信appid加密标识
	 * @return string
	 */
function getAppSecret(){
	return '';
}
	/**
	 * curl的get请求
	 * @return string
	 */
function httpGet($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
  }
	/**
	 * curl的post请求
	 * @return string
	 */
function httpPost($url, $param){
    $httph =curl_init($url);
    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($httph,CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    curl_setopt($httph, CURLOPT_POST, TRUE);//设置为POST方式 
    curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($httph, CURLOPT_HEADER,FALSE);
    $rst=curl_exec($httph);
    curl_close($httph);
    return $rst;
 }
function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }
?>