<?php

namespace Yoti\Http;

use Yoti\Exception\RequestException;

class Request
{
    // HTTP methods
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    /**
     * Methods that can send payloads.
     * You can add more method to this list separated by comma ','
     * We are using a const string, instead of a const array, to support PHP versions older than 5.6
     */
    const METHODS_THAT_INCLUDE_PAYLOAD = 'POST,PUT,PATCH';

    // Request HttpHeader keys
    const YOTI_AUTH_HEADER_KEY = 'X-Yoti-Auth-Key';
    const YOTI_DIGEST_HEADER_KEY = 'X-Yoti-Auth-Digest';
    const YOTI_SDK_IDENTIFIER_KEY = 'X-Yoti-SDK';

    /**
     * @var string
     */
    protected $pem;

    /**
     * @var string
     */
    protected $sdkId;

    /**
     * @var string
     */
    protected $connectApiUrl;

    /**
     * @var string
     */
    protected $sdkIdentifier;

    /**
     * @var string
     */
    protected $authKey;

    /**
     * Request constructor.
     *
     * @param string $connectApiUrl
     * @param string $pem
     * @param string $sdkId
     * @param string $sdkIdentifier
     *
     * @throws RequestException
     */
    public function __construct($connectApiUrl, $pem, $sdkId, $sdkIdentifier)
    {
        $this->pem = $pem;
        $this->sdkId = $sdkId;
        $this->connectApiUrl = $connectApiUrl;
        $this->sdkIdentifier = $sdkIdentifier;

        $this->authKey = $this->getAuthKeyFromPem();
    }

    /**
     * @param Payload $payload
     * @param string $endpoint
     * @param string $httpMethod
     *
     * @return array
     *
     * @throws RequestException
     */
    public function makeRequest(Payload $payload, $endpoint, $httpMethod)
    {
        Request::validateHttpMethod($httpMethod);

        $signedDataArr = RequestSigner::signRequest($this, $payload, $endpoint, $httpMethod);
        $requestHeaders = $this->getRequestHeaders($signedDataArr[RequestSigner::SIGNED_MESSAGE_KEY]);
        $requestUrl = $this->connectApiUrl . $signedDataArr[RequestSigner::END_POINT_PATH_KEY];

        return $this->executeRequest($payload, $requestHeaders, $requestUrl, $httpMethod);
    }

    /**
     * @return string
     */
    public function getSdkId()
    {
        return $this->sdkId;
    }

    /**
     * @return string
     */
    public function getPem()
    {
        return $this->pem;
    }

    /**
     * Return the request headers including the signed message.
     *
     * @param string $signedMessage
     *
     * @return array
     */
    protected function getRequestHeaders($signedMessage)
    {
        // Prepare request HttpHeaders
        return [
            REQUEST::YOTI_AUTH_HEADER_KEY . ": {$this->authKey}",
            REQUEST::YOTI_DIGEST_HEADER_KEY . ": {$signedMessage}",
            REQUEST::YOTI_SDK_IDENTIFIER_KEY . ": {$this->sdkIdentifier}",
            'Content-Type: application/json',
            'Accept: application/json',
        ];
    }

    /**
     * @return string
     *
     * @throws RequestException
     */
    private function getAuthKeyFromPem()
    {
        $details = openssl_pkey_get_details(openssl_pkey_get_private($this->pem));
        if (!array_key_exists('key', $details)) {
            return NULL;
        }

        // Remove BEGIN RSA PRIVATE KEY / END RSA PRIVATE KEY lines
        $key = trim($details['key']);
        // Support line break on *nix systems, OS, older OS, and Microsoft
        $_key = preg_split('/\r\n|\r|\n/', $key);
        if (strpos($key, 'BEGIN') !== FALSE) {
            array_shift($_key);
            array_pop($_key);
        }
        $key = implode('', $_key);

        // Check auth key is not empty
        if (empty($key)) {
            throw new RequestException('Could not retrieve key from PEM.', 401);
        }

        return $key;
    }

    /**
     * Check if the method can send Payloads data inside the Request body.
     *
     * @param string $httpMethod
     *
     * @return bool
     */
    public static function canSendPayload($httpMethod)
    {
        $methodsThatCanSendPayload = explode(',', self::METHODS_THAT_INCLUDE_PAYLOAD);
        return in_array($httpMethod, $methodsThatCanSendPayload, TRUE);
    }

    /**
     * Check if the provided HTTP method is valid.
     *
     * @param string $httpMethod
     *
     * @throws RequestException
     */
    private static function validateHttpMethod($httpMethod)
    {
        if (!Request::methodIsAllowed($httpMethod)) {
            throw new RequestException("Unsupported HTTP Method {$httpMethod}", 400);
        }
    }

    /**
     * Check the http method is allowed.
     *
     * @param string $httpMethod
     *
     * @return bool
     */
    private static function methodIsAllowed($httpMethod)
    {
        $allowedMethods = [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_PATCH,
            self::METHOD_DELETE,
        ];

        return in_array($httpMethod, $allowedMethods, TRUE);
    }

    /**
     * Execute Request against the API.
     *
     * @param Payload $payload
     * @param string $requestUrl
     * @param array $httpHeaders
     * @param string $httpMethod
     *
     * @return array
     *
     * @throws RequestException
     */
    protected function executeRequest(Payload $payload, array $httpHeaders, $requestUrl, $httpMethod)
    {
        $result = [
            'response' => '',
            'http_code'=> 0,
        ];

        $ch = curl_init($requestUrl);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $httpHeaders,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $httpMethod);

        // Only send payload data for methods that need it.
        if (Request::canSendPayload($httpMethod)) {
            // Send payload data as a JSON string
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload->getPayloadJSON());
        }

        // Set response data
        $result['response'] = curl_exec($ch);
        // Set response code
        $result['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check if any related Curl error occurred.
        if (curl_error($ch)) {
            throw new RequestException(curl_error($ch));
        }

        // Close the session
        curl_close($ch);

        return $result;
    }
}
