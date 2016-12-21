<?php
/**
 * 阿里云Oss操作定义 
 * ============================================================================
 * 版权所有 沈阳慧鼎商务服务有限公司，并保留所有权利。
 * 网站地址: 
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: mike狼
 * Date: 2016-10-25
 */

namespace Api\Logic;
/**
 * 阿里云 oss 操作定义
 * Class AliOssLogic
 * @package Index\Logic
 */
 use Api\Logic\CommonOssLogic;
class AliOssLogic extends CommonOssLogic
{
	
	/**
	 * 上传指定的本地文件内容
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	function uploadFile($object,$file)
	{
		$bucket = self::getBucketName();
		$ossClient = self::getOssClient();
		if (is_null($ossClient)) exit(1);
	    $filePath = $file;
	    $options = array();
	    try {
	        $ossClient->uploadFile($bucket, $object, $filePath, $options);
	    } catch (OssException $e) {
	       //可以配置微信 异常抛出发送人
	    }
	}
	
	
	/**
	 * 把本地变量的内容到文件[object保存的文件名][content内容]
	 *
	 * 简单上传,上传指定变量的内存值作为object的内容
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	function putObject($object,$content)
	{
		
		$bucket = self::getBucketName();
		$ossClient = self::getOssClient();	
	    $options = array();
	    try {
	        $ossClient->putObject($bucket, $object, $content, $options);
	    } catch (OssException $e) {
	    		jsonReturn(110,$e->getMessage());
	    }
	   
	}
	
	
	/**
	 * 服务端签名，客户端直接上传到oss服务器傻姑娘
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	public function ossClientSign(){
	$id= self::accessKeyId;
    $key= self::accesKeySecret;
    $host = C('HD_OSS_URL');

    $now = time();
    $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
    $end = $now + $expire;
    $expiration = gmt_iso8601($end);
	$twodir = I('dir','temp');
    $dir = 'phpUpload/'.$twodir."/";

    //最大文件大小.用户可以自己设置
    $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
    $conditions[] = $condition; 

    //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
    $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
    $conditions[] = $start; 


    $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
    //echo json_encode($arr);
    //return;
    $policy = json_encode($arr);
    $base64_policy = base64_encode($policy);
    $string_to_sign = $base64_policy;
    $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

    $response = array();
    $response['accessid'] = $id;
    $response['host'] = $host;
    $response['policy'] = $base64_policy;
    $response['signature'] = $signature;
    $response['expire'] = $end;
    //这个参数是设置用户上传指定的前缀
    $response['dir'] = $dir;
	jsonReturn(1,'成功',$response);
	}
	
	
	
    
    
    
}