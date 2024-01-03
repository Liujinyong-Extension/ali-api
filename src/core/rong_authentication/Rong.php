<?php

namespace Liujinyong\AliApi\core\rong_authentication;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dypnsapi\V20170525\Dypnsapi;
use AlibabaCloud\SDK\Dypnsapi\V20170525\Models\GetFusionAuthTokenRequest;
use AlibabaCloud\SDK\Dypnsapi\V20170525\Models\VerifyWithFusionAuthTokenRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Liujinyong\AliApi\exception\InvalidArgumentException;
use Liujinyong\AliApi\exception\SysErrorException;


class Rong
{


    private $accessKeyId;

    private $accessKeySecret;


    public function __construct($accessKeyId, $accessKeySecret)
    {
        $this->accessKeyId     = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
    }

    /**
     * 使用AK&SK初始化账号Client
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @return Dypnsapi Client
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
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @param string $securityToken
     * @return Dypnsapi Client
     */
    public static function createClientWithSTS($accessKeyId, $accessKeySecret, $securityToken)
    {
        $config = new Config([
                                 // 必填，您的 AccessKey ID
                                 "accessKeyId"     => $accessKeyId,
                                 // 必填，您的 AccessKey Secret
                                 "accessKeySecret" => $accessKeySecret,
                                 // 必填，您的 Security Token
                                 "securityToken"   => $securityToken,
                                 // 必填，表明使用 STS 方式
                                 "type"            => "sts"
                             ]);
        // Endpoint 请参考 https://api.aliyun.com/product/Dypnsapi
        $config->endpoint = "dypnsapi.aliyuncs.com";
        return new Dypnsapi($config);
    }


    public function gettoken($platform = "Android", $durationSeconds = 900, $schemeCode, $packageName = "", $packageSign = "", $bundleId = "")
    {
        // 请确保代码运行环境设置了环境变量 ALIBABA_CLOUD_ACCESS_KEY_ID 和 ALIBABA_CLOUD_ACCESS_KEY_SECRET。
        // 工程代码泄露可能会导致 AccessKey 泄露，并威胁账号下所有资源的安全性。以下代码示例仅供参考，建议使用更安全的 STS 方式，更多鉴权访问方式请参见：https://help.aliyun.com/document_detail/311677.html
        $params = [
            "platform"        => $platform,
            "durationSeconds" => $durationSeconds,
            "schemeCode"      => $schemeCode
        ];
        if ($platform == "Android") {
            if (empty($packageName) || empty($packageSign)) {
                throw new InvalidArgumentException("packageName or packageSign is empty");
            }
            $params["packageName"] = $packageName;
            $params["packageSign"] = $packageSign;
        } else {
            if (empty($bundleId)) {
                throw new InvalidArgumentException("bundleId is empty");
            }
            $params['bundleId'] = $bundleId;
        }

        $client = $this->createClient();


        $getFusionAuthTokenRequest = new GetFusionAuthTokenRequest($params);
        $runtime                   = new RuntimeOptions([]);
        try {
            $resp = $client->getFusionAuthTokenWithOptions($getFusionAuthTokenRequest, $runtime);
        } catch (\Exception $error) {
            throw new SysErrorException($error->getMessage(), 500);
        }
        return $resp;

    }

    /**
     * @param $verifyToken
     * @return \AlibabaCloud\SDK\Dypnsapi\V20170525\Models\VerifyWithFusionAuthTokenResponse
     * @throws SysErrorException
     */
    public function getPhone($verifyToken = "")
    {
        if (empty($verifyToken)) {
            throw new InvalidArgumentException("verifyToken is empty");
        }
        // 请确保代码运行环境设置了环境变量 ALIBABA_CLOUD_ACCESS_KEY_ID 和 ALIBABA_CLOUD_ACCESS_KEY_SECRET。
        // 工程代码泄露可能会导致 AccessKey 泄露，并威胁账号下所有资源的安全性。以下代码示例使用环境变量获取 AccessKey 的方式进行调用，仅供参考，建议使用更安全的 STS 方式，更多鉴权访问方式请参见：https://help.aliyun.com/document_detail/311677.html
        $client                           = $this->createClient(getenv("ALIBABA_CLOUD_ACCESS_KEY_ID"), getenv('ALIBABA_CLOUD_ACCESS_KEY_SECRET'));
        $verifyWithFusionAuthTokenRequest = new VerifyWithFusionAuthTokenRequest([
                                                                                     "verifyToken" => $verifyToken
                                                                                 ]);
        $runtime                          = new RuntimeOptions([]);
        try {
            // 复制代码运行请自行打印 API 的返回值
            $resp = $client->verifyWithFusionAuthTokenWithOptions($verifyWithFusionAuthTokenRequest, $runtime);
        } catch (\Exception $error) {
            throw new SysErrorException($error->getMessage(), 500);
        }
        return $resp;
    }

}