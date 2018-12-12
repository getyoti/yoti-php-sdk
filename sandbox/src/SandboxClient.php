<?php
namespace YotiSandbox;

use Yoti\YotiClient;
use YotiSandbox\Http\Response;
use Yoti\Http\CurlRequestHandler;
use YotiSandbox\Http\RequestBuilder;

class SandboxClient
{
    const TOKEN_REQUEST_ENDPOINT_FORMAT = "/app/%s/tokens";
    const DEFAULT_SANDBOX_API_URL = "https://api.yoti.com/sandbox/v1";

    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var CurlRequestHandler
     */
    private $requestHandler;

    /**
     * @var YotiClient
     */
    private $yotiClient;

    /**
     * SandboxClient constructor.
     *
     * @param string $sdkId
     * @param string $pem
     * @param string $sandboxApi
     * @param string $sdkIdentifier
     *
     * @throws \Yoti\Exception\RequestException
     * @throws \Yoti\Exception\YotiClientException
     */
    public function __construct($sdkId, $pem, $sandboxApi = self::DEFAULT_SANDBOX_API_URL, $sdkIdentifier = 'PHP')
    {
        $this->sdkId = $sdkId;
        $this->requestHandler = new CurlRequestHandler(
            $sandboxApi,
            $pem,
            $sdkId,
            $sdkIdentifier
        );

        $this->yotiClient = new YotiClient($sdkId, $pem);
    }

    /**
     * Return shared ActivityDetails.
     *
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
     * @param RequestBuilder $requestBuilder
     *
     * @param string $httpMethod
     *
     * @return string
     *
     * @throws Exception\ResponseException
     * @throws \Yoti\Exception\RequestException
     */
    public function getToken(RequestBuilder $requestBuilder, $httpMethod)
    {
        // Request endpoint
        $endpoint = sprintf(self::TOKEN_REQUEST_ENDPOINT_FORMAT, $this->sdkId);
        $resultArr = $this->sendRequest($requestBuilder, $endpoint, $httpMethod);

        return (new Response($resultArr))->getToken();
    }

    /**
     * @param RequestBuilder $requestBuilder
     *
     * @param string $endpoint
     * @param string $httpMethod
     *
     * @return array
     *
     * @throws \Yoti\Exception\RequestException
     */
    protected function sendRequest(RequestBuilder $requestBuilder, $endpoint, $httpMethod)
    {
        $resultArr = $this->requestHandler->sendRequest(
            $endpoint,
            $httpMethod,
            $requestBuilder->getRequest()->getPayload()
        );

        return $resultArr;
    }
}