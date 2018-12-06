<?php
namespace YotiSandbox;

use Yoti\YotiClient;
use Yoti\Http\CurlRequestHandler;
use YotiSandbox\Http\RequestBuilder;

class SandboxClient
{
    const YOTI_SANDBOX_PATH_PREFIX = "/sandbox/v1";
    const DEFAULT_YOTI_HOST = "https://api.yoti.com";
    const DEFAULT_SANDBOX_API_URL = "https://api.yoti.com/sandbox/v1";

    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var CurlRequestHandler
     */
    private $requestHandler;

    private $yotiClient;

    /**
     * SandboxClient constructor.
     *
     * @param string $sdkId
     * @param string $pem
     * @param string $connectApi
     * @param string $sdkIdentifier
     *
     * @throws \Yoti\Exception\RequestException
     * @throws \Yoti\Exception\YotiClientException
     */
    public function __construct($sdkId, $pem, $connectApi = self::DEFAULT_SANDBOX_API_URL, $sdkIdentifier = 'PHP')
    {
        $this->sdkId = $sdkId;
        $this->requestHandler = new \Yoti\Http\CurlRequestHandler(
            $connectApi,
            $pem,
            $sdkId,
            $sdkIdentifier
        );

        $this->yotiClient = new YotiClient($sdkId, $pem);
    }

    /**
     * @param string $token
     *
     * @return \Yoti\ActivityDetails
     *
     * @throws \Yoti\Exception\ActivityDetailsException
     * @throws \Yoti\Exception\ReceiptException
     */
    public function getActivityDetails($token)
    {
        return $this->yotiClient->getActivityDetails($token);
    }

    /**
     * @param string $httpMethod
     * @param RequestBuilder $requestBuilder
     *
     * @return mixed
     *
     * @throws \Yoti\Exception\RequestException
     * @throws \Exception
     */
    public function getTokenFromSandbox($httpMethod, RequestBuilder $requestBuilder)
    {
        // Request endpoint
        $endpoint = "/app/{$this->sdkId}/tokens";

        $responseArr = $this->requestHandler->sendRequest(
            $endpoint,
            $httpMethod,
            $requestBuilder->getPayload()
        );

        $this->checkResponseStatus($responseArr['http_code']);

        return $responseArr['token'];
    }

    /**
     * @param $httpCode
     *
     * @throws \Exception
     */
    private function checkResponseStatus($httpCode)
    {
        $httpCode = (int) $httpCode;
        if ($httpCode !== 200)
        {
            throw new \Exception("Server responded with {$httpCode}", $httpCode);
        }
    }
}