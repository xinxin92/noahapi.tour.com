<?php
namespace App\Library\Pay\AliPay;

class AliPay
{
    const API_VERSION = '1.0';
    const SIGN_TYPE = 'RSA2';
    const POST_CHARSET = 'utf-8';
    const FORMAT = 'json';

    //online
    const GATE_WAY_URL = 'https://openapi.alipay.com/gateway.do';
    const APP_ID = '';
    // 请填写开发者私钥去头去尾去回车，一行字符串
    const RSA_PRIVATE_KEY = '';
    // 请填写支付宝公钥，一行字符串
    const ALI_PAY_RSA_PUBLIC_KEY = '';

    // 沙箱环境 test
    const GATE_WAY_URL_TEST = 'https://openapi.alipaydev.com/gateway.do';
    const APP_ID_TEST = '2016080300155170';
    const RSA_PRIVATE_KEY_TEST = 'MIIEpAIBAAKCAQEA6aTiNBFx2FUAryO6ril/t2N/idSAj/3QlXKCLkFl6anXlHZ5hLddVFlsq5Z0jmTBcLqTcaExa65IPTCirA5hDSEPIgFoQ3NPqBmmoPnefTqOplisP97qECftIvmbEqAwTzvRvEo9mQ2ISTWSNSANuYSZrnHa+Hh8LkUy+kHwI2eqjSMAUFOx2v5XGBBeMhHvzu0y8uPxhmQgcIDlvb/UJW4cTVHa9LimiUW3VLCjWlgDzeQUm4qgcAX13hUlC5qPHQB6Vv8fR2Bm5JgRTXMkuL6jI61S0oapqGT5HCkksCV0v1UnaB/pOD4T/F6lLNydsAk+NbW6b/QkhLLa3ULrqwIDAQABAoIBAFkaEU3sDS+EEzO3e3zpxJQKdNv6V2ESWZZ0yeKb/CKlK9qpZ+RODWCajc/OG/slv4OfE5W09GTtXQ1A2/ijry1TmzLLuJhohEtGJP6k6810JGyZym69MyQgJdY9vH7Y6jN9S5nz8hPJTl0k+fIanEFQqU8WinxRboIyz6MSvGKzbn/B5IaLqRD3xfVPADjA4PazVX9q5f0zRntBYY6F0sqbKAVxDksIMoMPyXn7MFv5gfC31FNZxSZ3KBBBbAnIVuhM5kBKpOuWKLR5EyD3Vfy8vHXinE8jw7T/anc7vlH/b+TEN5lCU/zOEFOAMm6dnPewvTEIfx2QIKgsaCRTdZECgYEA++ztIP6mSBBJQ7ze62vElqnLJJhnRxcbD2WlseDe5MxH7DYZcL3Q8cw4YXOe8Fc3PZ/wF2P14F94uHZSgwG+NOiQLELfNjw3E8B3NISpGGdgTcikNhYE3nXJsZv7lW1cALqbgQFmah0jzhoEcd6Zy5U0IEvSS0M2TU1eekOAl4kCgYEA7WxDzLvnfomVtnPbwEZRBKAN/P6NnREW2/qaLCvgxJHS0JHgv7iVxIpjRyG+/BALr1iI+KBf9q/9MwEsSP7DjBydomAeGF0ASfJhijyUglXeMXAQP+HXbPnZfV8cofotfxQju01VUZhvD+YDkSHeMvVQ3LW5hNkbJqWAUOaqqJMCgYEA9v9ZkxL49wi4vGgua8VSjqFU4cFm6OV3i4YOibM+9jZvgeGc2dPRS3D+ClXxDBWGvK3WGSjRhjY0xy6DQZzw3zDxQeQisysgmQYIGf7DR4D7g+fnUHwQNAhqQu/7H4uEO9a55ib4QavmUgliOD6WrWrRG6UbPt1cHThxHBCYwHkCgYEAmHzsntYgvJjJW0HWZgvDhWD3GwKzGaocMHUoCC62UgoGiIbo/rojFvR7BviZnXMAzkHi1yzJ9brauKOMOeXG2nqWk+9/5M32nYfbNdnq2DsmVkJPbXgz0H44PdmUnkP8bJ4WxPnKTnnE+0UUDWbfO4NryBW/bZ+zXIL3gv5jpZkCgYB72PIDzyFSdGHAT81x25vPAo4EhJsEusbkd6UULJvLHIihlEFhXRyF6tX5lv+OWTUYvQo6kqENkViUqRX7L7clOpN0g3tKeqmDYLT2bJbE3Hi7spr+MRHV1ECky9OMr2p5wct3z9ANBQdYEdPT327jzkAF/AzUiku8q5STstwpXA==';
    const ALI_PAY_RSA_PUBLIC_KEY_TEST = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0yHMjFpy3aG8Ca+r8lRjF7IhBuvdqljKIsBHuCDE69p2bZyWfvJvcQ9fbdZhQp9eBmiRnfBX2Ja+51KNBcLBLVYjpVYBq1iu5Ubii8+G+4nk4BVq+hVN73zvgrQUoGi/qBLwdE/9wwhyEhqpiBoBXUBYCuETaHtZjAg6orV0cOAS7rUwMvz1xH2wgVPUTIwRmGCUeenvH/s6EaRo6z4VbYAREXo7GJEIirBdWraOEis8RDkwg2qRxobQMF6myQ1QgrH7R92QFmDJSY0LXkknFcOLmXX96/6MpcF4kUiyww8r9R8PhQoMBRHPyvZqHtMwpqW/TLzb9W6gQBrj9tmO3wIDAQAB';

    private $aop;
    public function __construct()
    {
        // init ali pay commen api params
        $this->aop = new \AopClient ();

        $this->aop->gatewayUrl = trim(self::GATE_WAY_URL_TEST);
        $this->aop->appId = trim(self::APP_ID_TEST);
        $this->aop->rsaPrivateKey = trim(self::RSA_PRIVATE_KEY_TEST);
        $this->aop->alipayrsaPublicKey= trim(self::ALI_PAY_RSA_PUBLIC_KEY_TEST);

        $this->aop->apiVersion = trim(self::API_VERSION);
        $this->aop->signType = trim(self::SIGN_TYPE);
        $this->aop->postCharset = trim(self::POST_CHARSET) ;
        $this->aop->format = trim(self::FORMAT);
    }

    /*
     * params @$outBizNo MUST 商户转账唯一订单号(发起转账来源方定义的转账单据ID，用于将转账回执通知给来源方。只支持半角英文、数字，及“-”、“_”)
     * params @$payeeAccount MUST 收款方账户(支付宝登录号，支持邮箱和手机号格式,付款方和收款方不能是同一个账户。)
     * parama @$amount MUST 转账金额，单位：元,只支持2位小数，小数点前最大支持13位，金额必须大于等于0.1元。
     * params @$payerShowName OPTIONAL 付款方显示姓名（最长支持100个英文/50个汉字）。 如果不传，则默认显示该账户在支付宝登记的实名。收款方可见。
     * params @$payeeRealName OPTIONAL 收款方真实姓名（最长支持100个英文/50个汉字）。 如果本参数不为空，则会校验该账户在支付宝登记的实名是否与收款方真实姓名一致。
     * params @$remark  OPTIONAL 转账备注（支持200个英文/100个汉字）。 当付款方为企业账户，且转账金额达到（大于等于）50000元，remark不能为空。收款方可见，会展示在收款用户的账单中。
     *
     * if success return [
     *          'code' => 10000,
     *          'msg' => "Success"
     *          "order_id" => "20160627110070001502260006780837",
     *          "out_biz_no" => "3142321423432",
     *          "pay_date" => "2013-01-01 08:08:08"
     *          ]
     * else return [
     *          'code' => int // -1,-2,-3,-100 self define code ; 20000,20001,40001,40002,40004,40006 alipay error code
     *          'msg' => xxx
     *          'sub_code' => xxx
     *          'sub_msg' => xxx
     * ]
     * alipay interface @https://doc.open.alipay.com/docs/api.htm?spm=a219a.7395905.0.0.pe5xhq&docType=4&apiId=1321
     * alipay error code @https://doc.open.alipay.com/docs/doc.htm?treeId=291&articleId=105806&docType=1
     */
    public function fundTransToAccount($outBizNo,$payeeAccount,$amount,$payerShowName = '',$payeeRealName = '',$remark = '')
    {
        // init params
        $bizContentParams = [];
        $bizContentParams['out_biz_no'] = $outBizNo;
        $bizContentParams['payee_type'] = 'ALIPAY_LOGONID'; // only support alipay_loginid
        $bizContentParams['payee_account'] = $payeeAccount;
        $bizContentParams['amount'] = $amount;
        if (!empty($payerShowName)) {
            $bizContentParams['payer_show_name'] = $payerShowName;
        }
        if (!empty($payeeRealName)) {
            $bizContentParams['payee_real_name'] = $payeeRealName;
        }
        if (!empty($remark)) {
            $bizContentParams['remark'] = $remark;
        }
        $bizContentParams = json_encode($bizContentParams);

        try {
            $request = new \AlipayFundTransToaccountTransferRequest ();
            $request->setBizContent($bizContentParams);
            $result = $this->aop->execute($request);
            $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
            if (empty($result)) {
                return ['code'=> -1,'msg'=> 'gate way接口返回为空'];
            }
            if (!isset($result->$responseNode) || empty($result->$responseNode)) {
                return ['code'=> -2,'msg'=> $responseNode. '接口返回为空'];
            }
            if (!isset($result->$responseNode->code) || empty($result->$responseNode->code)) {
                return ['code'=> -3,'msg'=> '支付宝接口没有返回code值'];
            }
            $resultCode = $result->$responseNode->code;
            if ($resultCode == 10000) {
                return [
                    'code'=> $resultCode,
                    'msg'=> $result->$responseNode->msg,
                    'order_id'=> $result->$responseNode->order_id,
                    'out_biz_no'=> $result->$responseNode->out_biz_no,
                    'pay_date' => $result->$responseNode->pay_date,
                ];
            } else {
                return [
                    'code'=> $resultCode,
                    'msg'=> $result->$responseNode->msg,
                    'sub_code'=>$result->$responseNode->sub_code,
                    'sub_msg'=>$result->$responseNode->sub_msg,
                ];
            }

        }catch (\Exception $exception) {
            return ['code'=> -100,'msg'=> $exception->getMessage(),'file'=> $exception->getFile(),'line'=> $exception->getLine()];
        }
    }

    /*
     * params @out_biz_no OPTIONAL 	商户转账唯一订单号：发起转账来源方定义的转账单据ID。 和支付宝转账单据号不能同时为空。当和支付宝转账单据号同时提供时，将用支付宝转账单据号进行查询，忽略本参数。
     * params @order_id OPTIONAL  支付宝转账单据号：和商户转账唯一订单号不能同时为空。当和商户转账唯一订单号同时提供时，将用本参数进行查询，忽略商户转账唯一订单号。
     *
     * if success return [
     *          'code' => 10000,
     *          'msg' => "Success"
     *          "order_id" => "20160627110070001502260006780837",
     *          "out_biz_no" => "3142321423432",
     *          "pay_date" => "2013-01-01 08:08:08",
     *          "status" =>''(OPTIONAL)
     *
     * ] else return [
     *          'code' => int // -1,-2,-3,-99,-100 self define code ; 20000,20001,40001,40002,40004,40006 alipay error code
     *          'msg' => xxx
     *          'sub_code' => xxx
     *          'sub_msg' => xxx
     * ]
     * alipay interface @https://doc.open.alipay.com/docs/api.htm?spm=a219a.7395905.0.0.yAESCD&docType=4&apiId=1322
     * alipay error code @https://doc.open.alipay.com/docs/doc.htm?treeId=291&articleId=105806&docType=1
    */
    public function fundTransOrderQuery($outBizNo = '',$orderId = '')
    {
        if (empty($outBizNo) && empty($orderId)) {
            return ['code' => -99, 'msg' => 'out_biz_no and order_id are both empty'];
        }
        $bizContentParams = [];
        if (!empty($outBizNo)) {
            $bizContentParams['out_biz_no'] = $outBizNo;
        }
        if (!empty($orderId)) {
            $bizContentParams['order_id'] = $orderId;
        }
        $bizContentParams = json_encode($bizContentParams);
        
        try {
            $request = new \AlipayFundTransOrderQueryRequest ();
            $request->setBizContent($bizContentParams);
            $result = $this->aop->execute ( $request);

            if (empty($result)) {
                return ['code'=> -1,'msg'=> 'gate way接口返回为空'];
            }
            $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
            if (!isset($result->$responseNode) || empty($result->$responseNode)) {
                return ['code'=> -2,'msg'=> $responseNode. '接口返回为空'];
            }
            if (!isset($result->$responseNode->code) || empty($result->$responseNode->code)) {
                return ['code'=> -3,'msg'=> '支付宝接口没有返回code值'];
            }
            $resultCode = $result->$responseNode->code;
            if ($resultCode == 10000) {
                return [
                    'code'=> $resultCode,
                    'msg'=> $result->$responseNode->msg,
                    'order_id'=> $result->$responseNode->order_id,
                    'status'=> isset($result->$responseNode->status)?$result->$responseNode->status:'',
                    'pay_date' => $result->$responseNode->pay_date,
                    'out_biz_no' => $result->$responseNode->out_biz_no,
                ];
            } else {
                return [
                    'code'=> $resultCode,
                    'msg'=> $result->$responseNode->msg,
                    'sub_code'=>$result->$responseNode->sub_code,
                    'sub_msg'=>$result->$responseNode->sub_msg,
                    'fail_reason'=>isset($result->$responseNode->fail_reason)?$result->$responseNode->fail_reason:'',
                    'error_code'=>isset($result->$responseNode->error_code)?$result->$responseNode->error_code:'',
                ];
            }
        } catch (\Exception $exception) {
            return ['code'=> -100,'msg'=> $exception->getMessage(),'file'=> $exception->getFile(),'line'=> $exception->getLine()];
        }
    }

}