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
class SkNotifyEvent implements PayNotifyInterface
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
			$dlq = A('DlqSign','Logic');
			$dlq->SetAppid('juyouweixin');//固定id
			$timeStamp = hmtime();
			$dlq->SetTimeStamp("$timeStamp");//时间戳
			$dlq->SetCode($data["order_no"]);//订单编号
			$dlq->SetTrade_no($data["transaction_id"]);
			$dlq->SetPaySign($dlq->MakeSign());
			$ret = $this->insertOrder($dlq->GetValues());
				if($ret!=false){
			       	 	return true;
			       }else{
			       		return false;
			       }
		}else{
			return FALSE;
		}
		
		
		
		
		
        // 执行业务逻辑，成功后返回true
//     	if($data['trade_state']=='success'){//支付成功
//     		$order = substr($data['order_no'],0,5);
//     		if($order=='lyxgh' || $order=='lyxyz'){
//     			$ret = $this->handleGhOrder($data);
//     		}else if($order=='lyxjf'){
//     			$ret = $this->JiaoFeiOrder($data);
//     		}else{
//     			$ret = $this->ShopOrder($data);
//     		}
//     	}
		
    }
    
    /*
     * 处理票务订单－调用dlq接口
     */
    private function insertOrder($data){
		$url = C('HD_BASE_URL').'/api/ss/tc/ticketorder/paybyweixin?timestamp='.$data['timestamp'].'&trade_no='.$data['trade_no'].'&jy_appid='.$data['jy_appid'].'&code='.$data['code'].'&res_sign='.$data['paySign'];
		$res = httpGet($url);
		$rest = json_decode($res,true);
		SetLog('调用dlq网址＝'.$url.'-----res='.$res, 'dlq.log');
		if($rest['errcode']!=0){//出票失败
			SetLog('调用dlq网址＝'.$url, 'dlq.log');
		return FALSE;
		}else{
			return TRUE;
		}
	}
    
    
    /*
     * 待定
     */
    private function JiaoFeiOrder($data){
    		
    }
    
   
    
}