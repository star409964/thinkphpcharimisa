<?php
require_once 'sample_base.php';
//初始化
$bucket = SampleUtil::get_bucket_name();
$oss = SampleUtil::get_oss_client();
//SampleUtil::create_bucket();

/*%**************************************************************************************************************%*/
//上传object 相关示例
/**
 *简单上传
 *上传指定变量的内存值
 */
 $object = "oss_test/upload-test-object-name.txt";
$content  = 'hello world';
$options = array(
    'content' => $content,
    'length' => strlen($content),
);
$res = $oss->upload_file_by_content($bucket, $object, $options);	
echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
$msg = "上传字符串到 /" . $bucket . "/" . $object;
OSSUtil::print_res($res, $msg);


/**
 *显示创建的bucket
 *列出用户所有的Bucket
 */

