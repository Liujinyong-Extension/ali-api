<?php

namespace Liujinyong\AliApi\core\number_authentication;

use AlibabaCloud\SDK\Dypnsapi\V20170525\Dypnsapi;
use AlibabaCloud\SDK\Dypnsapi\V20170525\Models\CheckSmsVerifyCodeRequest;
use AlibabaCloud\SDK\Dypnsapi\V20170525\Models\SendSmsVerifyCodeRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Darabonba\OpenApi\Models\Config;
use Liujinyong\AliApi\exception\InvalidSettingParam;
use Liujinyong\AliApi\exception\SysErrorException;

/**
 * 短信认证
 */
class Message
{
    private $accessKeyId;

    private $accessKeySecret;

    private $signName;
    private $templateCode;

    /**
     * @param $accessKeyId string 密钥ID
     * @param $accessKeySecret string 密钥
     * @param $signName string 签名
     * @param $templateCode string 模版code
     * @throws InvalidSettingParam
     */
    public function __construct($accessKeyId, $accessKeySecret, $signName, $templateCode)
    {
        if (empty($accessKeyId) || empty($accessKeySecret) || empty($signName) || empty($templateCode)) {
            throw new InvalidSettingParam("配置信息错误", 0);
        }

        $this->accessKeyId     = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
        $this->signName        = $signName;
        $this->templateCode    = $templateCode;
    }

    /**
     * @return Dypnsapi
     */

    public function createClient()
    {
        $config = new Config([
                                 // 必填，您的 AccessKey ID
                                 "accessKeyId"     => $this->accessKeyId,
                                 // 必填，您的 AccessKey Secret
                                 "accessKeySecret" => $this->accessKeySecret
                             ]);
        // Endpoint 请参考 https://api.aliyun.com/product/Dypnsapi
        $config->endpoint = "dypnsapi.aliyuncs.com";
        return new Dypnsapi($config);
    }

    /**
     * @param $phoneNumber string 手机号
     * @return \AlibabaCloud\SDK\Dypnsapi\V20170525\Models\SendSmsVerifyCodeResponse
     */
    public function send($phoneNumber)
    {
        // 请确保代码运行环境设置了环境变量 ALIBABA_CLOUD_ACCESS_KEY_ID 和 ALIBABA_CLOUD_ACCESS_KEY_SECRET。
        // 工程代码泄露可能会导致 AccessKey 泄露，并威胁账号下所有资源的安全性。以下代码示例仅供参考，建议使用更安全的 STS 方式，更多鉴权访问方式请参见：https://help.aliyun.com/document_detail/311677.html
        $client                   = $this->createClient();
        $sendSmsVerifyCodeRequest = new SendSmsVerifyCodeRequest([
                                                                     "phoneNumber"   => $phoneNumber,
                                                                     "signName"      => $this->signName,
                                                                     "templateCode"  => $this->templateCode,
                                                                     "templateParam" => "{\"code\":\"##code##\"}"
                                                                 ]);
        $runtime                  = new RuntimeOptions([]);
        try {
            $resp = $client->sendSmsVerifyCodeWithOptions($sendSmsVerifyCodeRequest, $runtime);
        } catch (\Exception $error) {

            throw new SysErrorException($error->getMessage(), 0);
        }
        return $resp;
    }

    /**
     * @param $phoneNumber string 电话号码
     * @param $code string 验证码
     * @return \AlibabaCloud\SDK\Dypnsapi\V20170525\Models\CheckSmsVerifyCodeResponse
     * @throws SysErrorException
     */
    public function check($phoneNumber, $code)
    {
        // 请确保代码运行环境设置了环境变量 ALIBABA_CLOUD_ACCESS_KEY_ID 和 ALIBABA_CLOUD_ACCESS_KEY_SECRET。
        // 工程代码泄露可能会导致 AccessKey 泄露，并威胁账号下所有资源的安全性。以下代码示例使用环境变量获取 AccessKey 的方式进行调用，仅供参考，建议使用更安全的 STS 方式，更多鉴权访问方式请参见：https://help.aliyun.com/document_detail/311677.html
        $client                    = $this->createClient();
        $checkSmsVerifyCodeRequest = new CheckSmsVerifyCodeRequest([
                                                                       "phoneNumber" => $phoneNumber,
                                                                       "verifyCode"  => $code
                                                                   ]);
        $runtime                   = new RuntimeOptions([]);
        try {
            // 复制代码运行请自行打印 API 的返回值
            $resp = $client->checkSmsVerifyCodeWithOptions($checkSmsVerifyCodeRequest, $runtime);
        } catch (\Exception $error) {

            throw new SysErrorException($error->getMessage(), 500);
        }
        return $resp;
    }

}