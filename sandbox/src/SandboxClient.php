<?php
namespace YotiSandbox;

use Yoti\YotiClient;
use YotiSandbox\Http\Response;
use Yoti\Http\CurlRequestHandler;
use YotiSandbox\Http\RequestBuilder;
use YotiSandbox\Http\SandboxPathManager;

class SandboxClient
{
    const TOKEN_REQUEST_ENDPOINT_FORMAT = "/apps/%s/tokens";

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
     * @param SandboxPathManager $sandboxPathManager
     * @param string $sdkIdentifier
     *
     * @throws \Yoti\Exception\RequestException
     * @throws \Yoti\Exception\YotiClientException
     */
    public function __construct($sdkId, $pem, SandboxPathManager $sandboxPathManager, $sdkIdentifier = 'PHP')
    {
        $this->sdkId = $sdkId;
        $pem = $this->includePemWrapper($pem);
        $this->requestHandler = new CurlRequestHandler(
            $sandboxPathManager->getTokenApiPath(),
            $pem,
            $sdkId,
            $sdkIdentifier
        );
        $this->yotiClient = new YotiClient($sdkId, $pem, $sandboxPathManager->getProfileApiPath());
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
        $payload = $requestBuilder->createRequest()->getPayload();

        $resultArr = $this->requestHandler->sendRequest(
            $endpoint,
            $httpMethod,
            $payload
        );

        return $resultArr;
    }

    private function includePemWrapper($pem)
    {
        if (strpos($pem, 'PRIVATE') === false) {
$pem = <<<EOF
-----BEGIN RSA PRIVATE KEY-----
{$pem}
-----END RSA PRIVATE KEY-----
EOF;
        }
        return $pem;
    }
}