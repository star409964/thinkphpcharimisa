<?php
/**
 * 微信的支付回调成功后->执行商客的订单完成逻辑
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

/**
 * 客户端需要继承该接口，并实现这个方法，在其中实现对应的业务逻辑
 * Class TestNotify
 * anthor helei
 */
namespace Api\Event;
Vendor('payment-paymentv2.autoload');
use Payment\Notify\PayNotifyInterface;
class CommonNotifyEvent implements PayNotifyInterface
{
	/*
	 * 
	 * data 数据
	 * {
		    "subject": "测试支付",
		    "body": "支付接口测试",
		    "amount": "0.01",
		    "channel": "ali",
		    "order_no": "2016091712494224",
		    "buyer_id": "miao_hui1113@126.com",
		    "trade_state": "success",
		    "transaction_id": "2016091721001004620263393888",
		    "time_end": "2016-09-17 12:49:51",
		    "notify_time": "2016-09-17 12:49:51",
		    "notify_type": "trade"
		}
	 * 
	 */
    public function notifyProcess(array $data)
    {
    	
		SetLog('支付回调参数='.json_encode($data), 'sknotify.log');	
		if(array_key_exists("order_no", $data)){
			$data['type'] = 1; 
			$data['amount'] = $data['amount']*100;
			$ret = D("SysWxchatCallbackLog")->add($data);
			SetLog('支付结果='.json_encode($ret), 'sknotify.log');	
				if($ret!=false){
			       	 	return true;
			       }else{
			       		return false;
			       }
		}else{
			return FALSE;
		}
		
    }
    
}//class end