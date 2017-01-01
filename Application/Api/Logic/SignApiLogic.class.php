<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */ 
namespace Api\Logic;
class SignApiLogic{
	
	const Sign_KEY	= 'wangliang';
	protected $values = array();
	
	
   /*
     * 初始化操作 
     */
    public function __construct() {
        $local_sign = $this->getSign();
        $api_secret_key = C('API_SECRET_KEY');
        $sign = $_POST['sign'];
        $timestamp = $_POST['timestamp'];
         if(empty($sign) || empty($timestamp)) {
         	jsonReturn(110,'缺少签名参数或时间戳参数');
		 }
            
        // 不参与签名验证的方法
        if(!in_array(strtolower(ACTION_NAME), array('gettestsign','getconfig')))
        {        
            if($local_sign != $_POST['sign'])
            {    
               jsonReturn(110,'签名失败');

            }
            if(time() - $_POST['timestamp'] > 600)
            {    
               jsonReturn(110,'请求超市');
            }
        }       
    }
   
   
   
   
   /**
	 * 格式化参数格式化成url参数
	 */
	public function ToUrlParams()
	{
		$buff = "";
		foreach ($this->values as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
		$buff = trim($buff, "&");
		return $buff;
	}
	
   /**
     * 获取签名--测试
     * @return type
     */
    public function gettestsign(){
        header("Content-type:text/html;charset=utf-8");
        $data['openid'] = 'oZ9t5uCdJsbCK_Us9r_I2xWujsOc';
        $data['amount'] = '100';
        $data['desc'] = I('desc');
        $data['billno'] = 'syly3434343';
        $data['timestamp'] = time();
        
        
         $this->values = $data;
      	 ksort($this->values);
		$string = $this->ToUrlParams();
		//签名步骤二：在string后加入KEY
		$string = $string . "&jy_encodingkey=".self::Sign_KEY;
		echo $string.'</br>';
		//签名步骤三：MD5加密
		$string = md5($string);
		echo 'time='.$data['timestamp'].'</br>';
		echo 'sign='.$string;
    }
	
   /**
     * app端请求签名
     * @return type
     */
    protected function getSign(){
        header("Content-type:text/html;charset=utf-8");
        $data = $_POST;        
        //unset($data['timestamp']);    // 删除这两个参数再来进行排序     
        unset($data['sign']);    // 删除这两个参数再来进行排序
         $this->values = $data;
      	 ksort($this->values);
		$string = $this->ToUrlParams();
		//签名步骤二：在string后加入KEY
		$string = $string . "&jy_encodingkey=".self::Sign_KEY;
		//签名步骤三：MD5加密
		$string = md5($string);
		return $string;
    }
   
   	/*
	 * 给用户发送红包[data数组详情看wxpaylogic]
	 */
   	public function sendMoneyUser($wxid,$data){
   		$pay = A('WxPay','Logic');
		$res = $pay->sendRedPackage($wxid,$data);
		jsonReturn(100,'通信成功',$res);
   	}
   
  	 /*
	  * 微信退款
	  * 商户订单号
	  * 原订单总金额（分）
	  * 退款金额（分）
	  */
	 public function refund($out_trade_no,$total_fee,$refund_fee){
	 	
		
	 }
	 //------微信退款
   
}//class end