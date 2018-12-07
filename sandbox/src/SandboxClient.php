<?php
namespace YotiSandbox;

use Yoti\YotiClient;
use Yoti\Http\CurlRequestHandler;
use YotiSandbox\Http\Response;
use YotiSandbox\Http\RequestBuilder;

class SandboxClient
{
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
        $this->requestHandler = new CurlRequestHandler(
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
     * @return string
     *
     * @throws \Yoti\Exception\RequestException
     * @throws Exception\ResponseException
     */
    public function getTokenFromSandbox($httpMethod, RequestBuilder $requestBuilder)
    {
        // Request endpoint
        $endpoint = "/app/{$this->sdkId}/tokens";

        $resultArr = $this->requestHandler->sendRequest(
            $endpoint,
            $httpMethod,
            $requestBuilder->getPayload()
        );

        return (new Response($resultArr))->getToken();
    }
}