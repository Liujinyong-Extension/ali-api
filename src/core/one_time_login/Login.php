<?php

namespace Liujinyong\AliApi\core\one_time_login;

use AlibabaCloud\SDK\Dypnsapi\V20170525\Dypnsapi;
use AlibabaCloud\SDK\Dypnsapi\V20170525\Models\GetMobileRequest;
use AlibabaCloud\SDK\Dypnsapi\V20170525\Models\VerifyMobileRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Darabonba\OpenApi\Models\Config;
use Liujinyong\AliApi\exception\SysErrorException;

class Login
{


    private $accessKeyId;

    private $accessKeySecret;

    public function __construct($accessKeyId, $accessKeySecret)
    {
        $this->accessKeyId     = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
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
     * 使用STS鉴权方式初始化账号Client，推荐此方式。
     * @param string $securityToken
     * @return Dypnsapi Client
     */
    public function createClientWithSTS($securityToken)
    {
        $config = new Config([
                                 // 必填，您的 AccessKey ID
                                 "accessKeyId"     => $this->accessKeyId,
                                 // 必填，您的 AccessKey Secret
                                 "accessKeySecret" => $this->accessKeySecret,
                                 // 必填，您的 Security Token
                                 "securityToken"   => $securityToken,
                                 // 必填，表明使用 STS 方式
                                 "type"            => "sts"
                             ]);
        // Endpoint 请参考 https://api.aliyun.com/product/Dypnsapi
        $config->endpoint = "dypnsapi.aliyuncs.com";
        return new Dypnsapi($config);
    }

    /**
     * @param $accessToken string 访问token
     * @return \AlibabaCloud\SDK\Dypnsapi\V20170525\Models\GetMobileResponse
     * @throws SysErrorException
     */
    public function getPhone($accessToken)
    {
        // 请确保代码运行环境设置了环境变量 ALIBABA_CLOUD_ACCESS_KEY_ID 和 ALIBABA_CLOUD_ACCESS_KEY_SECRET。
        // 工程代码泄露可能会导致 AccessKey 泄露，并威胁账号下所有资源的安全性。以下代码示例仅供参考，建议使用更安全的 STS 方式，更多鉴权访问方式请参见：https://help.aliyun.com/document_detail/311677.html
        $client           = $this->createClient();
        $getMobileRequest = new GetMobileRequest([
                                                     "accessToken" => $accessToken
                                                 ]);
        $runtime          = new RuntimeOptions([]);
        try {
            $resp = $client->getMobileWithOptions($getMobileRequest, $runtime);
        } catch (\Exception $error) {
            throw new SysErrorException($error->getMessage(), 500);
        }
        return $resp;
    }

    /**
     * @param $accessToken string 访问token
     * @param $phoneNumber  string 手机号码
     * @return \AlibabaCloud\SDK\Dypnsapi\V20170525\Models\VerifyMobileResponse
     * @throws SysErrorException
     */
    public function checkPhone($accessToken,$phoneNumber){

        // 请确保代码运行环境设置了环境变量 ALIBABA_CLOUD_ACCESS_KEY_ID 和 ALIBABA_CLOUD_ACCESS_KEY_SECRET。
        // 工程代码泄露可能会导致 AccessKey 泄露，并威胁账号下所有资源的安全性。以下代码示例仅供参考，建议使用更安全的 STS 方式，更多鉴权访问方式请参见：https://help.aliyun.com/document_detail/311677.html
        $client = $this->createClient();
        $verifyMobileRequest = new VerifyMobileRequest([
                                                           "accessCode" => $accessToken,
                                                           "phoneNumber" => $phoneNumber
                                                       ]);
        $runtime = new RuntimeOptions([]);
        try {
            $resp = $client->verifyMobileWithOptions($verifyMobileRequest, $runtime);
        }
        catch (\Exception $error) {
            throw new SysErrorException($error->getMessage(), 500);

        }
        return $resp;
    }
}