<?php
namespace Yoti\Http;

class SignedRequest
{
    /**
     * HTTP method.
     *
     * @var string
     */
    private $httpMethod;

    /**
     * Request endpoint.
     *
     * @var string
     */
    private $endpoint;

    /**
     * SDK ID from dashboard.
     *
     * @var string
     */
    private $sdkId;

    /**
     * @var Payload
     */
    private $payload;

    /**
     * PEM key.
     *
     * @var string
     */
    private $pem;

    /**
     * Request full endpoint path.
     *
     * @var string
     */
    private $endpointPath;

    /**
     * SignedRequest constructor.
     *
     * @param Payload $payload
     * @param string $endpoint
     * @param string $pem
     * @param string $sdkId
     * @param string $httpMethod
     *
     * @throws \Exception
     */
    public function __construct(Payload $payload, $endpoint, $pem, $sdkId, $httpMethod = 'GET')
    {
        $this->httpMethod = $httpMethod;
        $this->endpoint = $endpoint;
        $this->payload = $payload;
        $this->pem = $pem;
        $this->sdkId = $sdkId;

        $this->checkEndpoint($this->endpoint);
        RestRequest::checkHttpMethod($this->httpMethod);
        $this->generatePath();
    }

    /**
     * Get signed message.
     *
     * @return string
     */
    public function getSignedMessage()
    {
        $endpointRequest = "{$this->httpMethod}&$this->endpointPath";
        if(RestRequest::canSendPayload($this->httpMethod))
        {
            $endpointRequest .= "&{$this->payload->getBase64Payload()}";
        }

        openssl_sign($endpointRequest, $signature, $this->pem, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }

    /**
     * Get API URL request.
     *
     * @param string $apiUrl
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getApiRequestUrl($apiUrl)
    {
        if(!$this->isValidUrl($apiUrl))
        {
            throw new \Exception('Invalid Api Url', 400);
        }

        return $apiUrl . $this->endpointPath;
    }

    /**
     * Validate URL.
     *
     * @param string $url
     *
     * @return bool
     */
    public function isValidUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) ? TRUE : FALSE;
    }

    /**
     * Return API full endpoint.
     *
     * @return string
     */
    public function getEndpointPath()
    {
        return $this->endpointPath;
    }

    /**
     * Generate API full endpoint.
     */
    public function generatePath()
    {
        // Prepare message to sign
        $nonce = $this->generateNonce();
        $timestamp = round(microtime(true) * 1000);

        $path = "{$this->endpoint}?nonce={$nonce}&timestamp={$timestamp}&appId={$this->sdkId}";

        $this->endpointPath = $path;
    }

    /**
     * Check if the endpoint is valid.
     *
     * @param string $endpoint
     *
     * @throws \Exception
     */
    public function checkEndpoint($endpoint)
    {
        if(empty($endpoint) || $endpoint[0] !== '/')
        {
            throw new \Exception('Invalid endpoint', 400);
        }
    }

    /**
     * @return string
     */
    private function generateNonce()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}