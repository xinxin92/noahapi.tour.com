<?php
//支付宝支付测试
namespace App\Http\Controllers\Test;

use App\Library\Common;
use App\Library\Pay\AliPay\AliPay;

class TestAlipay extends TestBase
{
    //支付
    public function pay()
    {
        $orderNumber = Common::getUniqueValue();
        $AliPayLib = new AliPay();
        $payResult = $AliPayLib->fundTransToAccount($orderNumber,'wbumbm2382@sandbox.com',1.5,'测试','沙箱环境','测试备注');
        return $payResult;
    }

    //支付结果查询
    public function query()
    {
        $outBizNo = $this->request['orderNumber'];
        $AliPayLib = new AliPay();
        $payResult = $AliPayLib->fundTransOrderQuery($outBizNo);
        return $payResult;
    }
}
