<?php

namespace Yoti\Http;

class Request
{
    protected $pem;
    protected $appId;
    protected $clientApiUrl;
    protected $requestSigner;
    protected $sdkIdentifier;

    public function __construct(RequestSigner $requestSigner, $clientApiUrl, $pem, $appId, $sdkIdentifier)
    {
        $this->pem = $pem;
        $this->appId = $appId;
        $this->clientApiUrl = $clientApiUrl;
        $this->sdkIdentifier = $sdkIdentifier;
        $this->requestSigner = $requestSigner;
    }

    public function makeRequest(Payload $payload, $endpoint, $httpMethod = 'GET')
    {
        $headers = $this->getRequestHeaders();
        $requestUrl = $this->apiUrl . $endpoint;
    }

    private function getRequestHeaders()
    {
        $signedMessage = $this->requestSigner->signRequest($this);
        $authKey = $this->getAuthKeyFromPem();

        // Check auth key
        if(!$authKey)
        {
            throw new \Exception('Could not retrieve key from PEM.', 401);
        }

        // Check signed message
        if(!$signedMessage)
        {
            throw new \Exception('Could not sign request.', 401);
        }

        // Prepare request httpHeaders
        return [
            self::AUTH_KEY_HEADER . ": {$authKey}",
            self::DIGEST_HEADER . ": {$signedMessage}",
            self::YOTI_SDK_HEADER . ": {$this->sdkIdentifier}",
            'Content-Type: application/json',
            'Accept: application/json',
        ];
    }

    /**
     * @return null|string
     */
    private function getAuthKeyFromPem()
    {
        $details = openssl_pkey_get_details(openssl_pkey_get_private($this->_pem));
        if(!array_key_exists('key', $details))
        {
            return NULL;
        }

        // Remove BEGIN RSA PRIVATE KEY / END RSA PRIVATE KEY lines
        $key = trim($details['key']);
        // Support line break on *nix systems, OS, older OS, and Microsoft
        $_key = preg_split('/\r\n|\r|\n/', $key);
        if(strpos($key, 'BEGIN') !== FALSE)
        {
            array_shift($_key);
            array_pop($_key);
        }
        $key = implode('', $_key);

        return $key;
    }
}