<?php

namespace YotiSandbox;

use Psr\Http\Client\ClientInterface;
use Yoti\Http\Payload;
use Yoti\YotiClient;
use Yoti\Http\RequestBuilder;
use YotiSandbox\Http\SandboxPathManager;
use YotiSandbox\Http\TokenRequest;
use YotiSandbox\Http\TokenResponse;

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
     * @var \Psr\Http\Client\ClientInterface|null
     */
    private $httpClient;

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
     * @param \Psr\Http\Client\ClientInterfaces $httpClient
     *
     * @throws \Yoti\Exception\RequestException
     * @throws \Yoti\Exception\YotiClientException
     */
    public function __construct(
        $sdkId,
        $pem,
        SandboxPathManager $sandboxPathManager,
        $sdkIdentifier = 'PHP',
        ClientInterface $httpClient = null
    ) {
        $this->sdkId = $sdkId;
        $this->sdkIdentifier = $sdkIdentifier;
        $this->pem = $this->includePemWrapper($pem);
        $this->sandboxPathManager = $sandboxPathManager;
        $this->httpClient = $httpClient;

        $this->yotiClient = new YotiClient($sdkId, $this->pem, $sandboxPathManager->getProfileApiPath());

        if (isset($this->httpClient)) {
            $this->yotiClient->setHttpClient($httpClient);
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
     * @param \YotiSandbox\Http\TokenRequest $tokenRequest
     *
     * @return string
     *
     * @throws Exception\ResponseException
     * @throws \Yoti\Exception\RequestException
     */
    public function getToken(TokenRequest $tokenRequest)
    {
        // Request endpoint
        $endpoint = sprintf(self::TOKEN_REQUEST_ENDPOINT_FORMAT, $this->sdkId);
        $response = $this->sendRequest($tokenRequest->getPayload(), $endpoint, 'POST');

        return (new TokenResponse($response))->getToken();
    }

    /**
     * @param \Yoti\Http\Payload $payload
     *
     * @param string $endpoint
     * @param string $httpMethod
     *
     * @return array
     *
     * @throws \Yoti\Exception\RequestException
     */
    private function sendRequest(Payload $payload, $endpoint, $httpMethod)
    {
        $requestBuilder = (new RequestBuilder())
            ->withBaseUrl($this->sandboxPathManager->getTokenApiPath())
            ->withEndpoint($endpoint)
            ->withMethod($httpMethod)
            ->withSdkIdentifier($this->sdkIdentifier)
            ->withPemString($this->pem)
            ->withPayload($payload)
            ->withQueryParam('appId', $this->sdkId);

        if (isset($this->httpClient)) {
            $requestBuilder->withClient($this->httpClient);
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
