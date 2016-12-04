<?php
/**
 * @author: helei
 * @createTime: 2016-07-14 18:20
 * @description: 支付宝移动支付接口
 * @link      https://github.com/helei112g/payment/tree/paymentv2
 * @link      https://helei112g.github.io/
 */

namespace Payment\Charge\Ali;


use Payment\Common\Ali\AliBaseStrategy;
use Payment\Common\Ali\Data\Charge\AppChargeData;
use Payment\Utils\ArrayUtil;

class AliAppCharge extends AliBaseStrategy
{
    /**
     * 获取支付对应的数据完成类
     * @return string
     * @author helei
     */
    protected function getBuildDataClass()
    {
        // 以下两种方式任选一种
        //return AppChargeData::class;

        return 'Payment\Common\Ali\Data\Charge\AppChargeData';
    }

    protected function retData(array $data)
    {	//dump($data);
    		$arr['sign'] = urlencode($data['sign']);
    		$arr['sign_type'] = $data['sign_type'];
    		$values = ArrayUtil::removeKeys($data, ['sign', 'sign_type']);
        $values = ArrayUtil::arraySort($values);
       // dump($values);
        // 如果是移动支付，直接返回数据信息。并且对sign做urlencode编码
        return ArrayUtil::createLinkstring($values).'&sign="'.$arr['sign'].'"';
    }
}