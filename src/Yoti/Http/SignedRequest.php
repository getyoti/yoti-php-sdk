<?php
namespace Yoti\Http;

class SignedRequest
{
    /**
     * Http method.
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
     * SignedRequest constructor.
     *
     * @param Payload $payload
     * @param $endpoint
     * @param $pem
     * @param $sdkId
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
        $this->checkRequestMethod($this->httpMethod);
    }

    /**
     * Get signed message.
     *
     * @return string
     */
    public function getSignedMessage()
    {
        $endpointPath = $this->getEndpointPath();

        $endpointPointRequest = "{$this->httpMethod}&$endpointPath";
        openssl_sign($endpointPointRequest, $signature, $this->pem, OPENSSL_ALGO_SHA256);
        $messageSignature = base64_encode($signature);

        return $messageSignature;
    }

    /**
     * Return Yoti dashboard endpoint including the payload.
     *
     * @return string
     */
    private function getEndpointPath()
    {
        // Prepare message to sign
        $nonce = $this->generateNonce();
        $timestamp = round(microtime(true) * 1000);
        // Serialize the array into a string
        $payload = base64_encode(serialize($this->payload->getByteArray()));
        $path = "{$this->endpoint}?nonce={$nonce}&timestamp={$timestamp}&appId={$this->sdkId}";
        $path .= "&payload={$payload}";

        return $path;
    }

    /**
     * Check the endpoint is valid.
     *
     * @param $endpoint
     *
     * @throws \Exception
     */
    public function checkEndpoint($endpoint)
    {
        if(empty($endpoint) || $endpoint[0] !== '/') {
            throw new \Exception('Invalid endpoint', 401);
        }
    }

    /**
     * Check request method.
     *
     * @param $httpMethod
     * @throws \Exception
     */
    public function checkRequestMethod($httpMethod)
    {
        if(empty($httpMethod)) {
            throw new \Exception('Http method cannot be empty', 401);
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