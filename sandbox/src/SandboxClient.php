<?php
namespace YotiSandbox;

use Yoti\YotiClient;
use YotiSandbox\Http\Response;
use Yoti\Http\RequestHandlerInterface;
use YotiSandbox\Http\RequestBuilder;
use Yoti\Http\RequestBuilder as YotiRequestBuilder;
use YotiSandbox\Http\SandboxPathManager;

class SandboxClient
{
    const TOKEN_REQUEST_ENDPOINT_FORMAT = "/apps/%s/tokens";

    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var string
     */
    private $sdkIdentifier;

    /**
     * @var string
     */
    private $pem;

    /**
     * @var \YotiSandbox\Http\SandboxPathManager
     */
    private $sandboxPathManager;

    /**
     * @var \Yoti\Http\RequestHandlerInterface|null
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
     * @param \YotiSandbox\Http\SandboxPathManager $sandboxPathManager
     * @param string $sdkIdentifier
     * @param \Yoti\Http\RequestHandlerInterface $requestHandler
     *
     * @throws \Yoti\Exception\RequestException
     * @throws \Yoti\Exception\YotiClientException
     */
    public function __construct(
        $sdkId,
        $pem,
        SandboxPathManager $sandboxPathManager,
        $sdkIdentifier = 'PHP',
        RequestHandlerInterface $requestHandler = null
    )
    {
        $this->sdkId = $sdkId;
        $this->sdkIdentifier = $sdkIdentifier;
        $this->pem = $this->includePemWrapper($pem);
        $this->sandboxPathManager = $sandboxPathManager;
        $this->requestHandler = $requestHandler;

        $this->yotiClient = new YotiClient($sdkId, $this->pem, $sandboxPathManager->getProfileApiPath());

        if (isset($this->requestHandler)) {
            $this->yotiClient->setRequestHandler($requestHandler);
        }
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
     * @param \YotiSandbox\Http\RequestBuilder $requestBuilder
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
        $response = $this->sendRequest($requestBuilder, $endpoint, $httpMethod);

        return (new Response($response))->getToken();
    }

    /**
     * @param \YotiSandbox\Http\RequestBuilder $requestBuilder
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

        $requestBuilder = (new YotiRequestBuilder())
            ->withBaseUrl($this->sandboxPathManager->getTokenApiPath())
            ->withEndpoint($endpoint)
            ->withMethod($httpMethod)
            ->withSdkIdentifier($this->sdkIdentifier)
            ->withPemString($this->pem)
            ->withPayload($payload)
            ->withQueryParam('appId', $this->sdkId);

        if (isset($this->requestHandler)) {
            $requestBuilder->withHandler($this->requestHandler);
        }

        return $requestBuilder->build()->execute();
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