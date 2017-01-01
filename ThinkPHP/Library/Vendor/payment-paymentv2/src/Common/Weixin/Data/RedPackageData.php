<?php
/**
 * @author: mike狼
 * @createTime: 2017-1-1 09:42
 * @description:
 */

namespace Payment\Common\Weixin\Data;
use Payment\Common\PayException;
use Payment\Utils\ArrayUtil;


/**
 * Class TransferData
 *
 * 微信红包数据
 *
 * @property string $trans_no  转账单号
 * @property string $total_amount  等于   $trans_data['total_amount'] 的值
 * @property string $openid 等于   $trans_data['user_account'] 的值
 * @property string $user_name 等于 $trans_data['user_name'] 的值
 * @property string $desc 等于 $trans_data['desc'] 的值
 * @property array $trans_data  红包详细数据
 *  $trans_data[] = [
 *      'user_account'   => '收款账号',
 *      'user_name'     => '收款人姓名',
 *      'trans_fee'       => '付款金额',  // 传入时单位为元
 *  ];
 *
 * @package Payment\Common\Weixin\Data
 * anthor helei
 */
class RedPackageData extends WxBaseData
{

    protected function buildData()
    {
        $this->retData = [
            'wxappid' => $this->appId,
            'mch_id'    => $this->mchId,
            'nonce_str' => $this->nonceStr,
            'mch_billno'    => $this->trans_no,
            're_openid'    => $this->openid,
			
            'total_num'    => 1, //红包发放总人数
            'send_name'    => $this->sendName, //红包发送者名称
            'total_amount'    => $this->trans_fee,// 此处需要处理单位为分
            'wishing'  => $this->wishing,//红包祝福语
            'act_name'  => $this->actName,//活动名称
            'remark'  => $this->remark,//备注
            'scene_id'  => $this->sceneId,// 红包金额大于200的时候 需要传递这个值

            // $_SERVER["REMOTE_ADDR"]  获取客户端接口。此处获取php所在机器的ip  如果无法获取，则使用该ip
            'client_ip'  => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
        ];

        $this->retData = ArrayUtil::paraFilter($this->retData);
    }

    /**
     * 检查相关参数是否设置
     * @author helei
     */
    protected function checkDataParam()
    {
        $data = $this->trans_data;
        $transNo = $this->trans_no;// 付款交易号，商户系统内唯一
        // 当前微信转款仅支持单笔
        if (sizeof($data) != 1) {
            throw new PayException('当前版本，不支持微信批量退款。目前仅支持1笔');
        }

        // 检查付款单号是否设置
        if (empty($transNo) || mb_strlen($transNo) < 11 || mb_strlen($transNo) > 32) {
            throw new PayException('转账单号，不能为空，长度在11~32位之间');
        }

        foreach ($data as $key => $item) {

            if (empty($item['user_account'])) {
                throw new PayException('该值必须设置，为关注者的openid');
            }
            $this->openid = $item['user_account'];

            // 检查付款金额  微信转款，最小金额为1元
            if (bccomp($item['trans_fee'], '1', 2) === -1) {
                throw new PayException("交易金额不能小于 1 元");
            }
            $this->trans_fee = bcmul($item['trans_fee'], 100, 0);// 微信以分为单位

            // 检查收款方姓名
            if (empty($item['total_num'])) {
                throw new PayException("红包发送数量不能空");
            }
            $this->totalNum = $item['total_num'];

            if (empty($item['send_name']) || mb_strlen($item['send_name']) > 10) {
                throw new PayException("发送者不能为空，并且不能超过10个字符");
            }
            $this->sendName = $item['send_name'];
			
			if (empty($item['wishing']) || mb_strlen($item['wishing']) > 32) {
                throw new PayException("祝福语不能为空，并且不能超过32个汉字");
            }
            $this->wishing = $item['wishing'];
			
			if (empty($item['act_name']) || mb_strlen($item['act_name']) > 10) {
                throw new PayException("活动名称不能为空，并且不能超过10个字符,目前是".mb_strlen($item['act_name']));
            }
            $this->actName = $item['act_name'];
			
			if (empty($item['remark']) || mb_strlen($item['remark']) > 85) {
                throw new PayException("备注名称不能为空，并且不能超过85个字符");
            }
            $this->remark = $item['remark'];
			
			// 检查付款金额  微信转款，最小金额为1元
            if ($item['trans_fee']>200) {
            		if(empty($item['scene_id'])){
               		 throw new PayException("红包金额大于200，必须传入scene_id");
            		}
				$this->sceneId = $item['scene_id'];
            }
        }
    }
}