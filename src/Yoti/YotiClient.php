<?php

namespace Yoti;

use Yoti\Entity\Receipt;
use Yoti\Http\Payload;
use Yoti\Http\AmlResult;
use Yoti\Entity\AmlProfile;
use Yoti\Http\SignedRequest;
use Yoti\Http\RestRequest;
use Yoti\Exception\AmlException;
use Yoti\Exception\ReceiptException;
use Yoti\Exception\ActivityDetailsException;

/**
 * Class YotiClient
 *
 * @package Yoti
 * @author Yoti SDK <websdk@yoti.com>
 */
class YotiClient
{
    /**
     * Request successful outcome
     */
    const OUTCOME_SUCCESS = 'SUCCESS';

    // Default url for api (is passed in via constructor)
    const DEFAULT_CONNECT_API = 'https://api.yoti.com:443/api/v1';

    // Base url for connect page (user will be redirected to this page eg. baseurl/app-id)
    const CONNECT_BASE_URL = 'https://www.yoti.com/connect';

    // Dashboard login
    const DASHBOARD_URL = 'https://www.yoti.com/dashboard';

    // Connect request httpHeader keys
    const AUTH_KEY_HEADER = 'X-Yoti-Auth-Key';
    const DIGEST_HEADER = 'X-Yoti-Auth-Digest';
    const YOTI_SDK_HEADER = 'X-Yoti-SDK';

    // Aml check endpoint
    const AML_CHECK_ENDPOINT = '/aml-check';

    /**
     * Accepted HTTP header values for X-Yoti-SDK header.
     *
     * @var array
     */
    private $acceptedSDKIdentifiers = [
        'PHP',
        'WordPress',
        'Drupal',
        'Joomla',
    ];

    /**
     * @var string
     */
    private $_connectApi;

    /**
     * @var string
     */
    private $_sdkId;

    /**
     * @var string
     */
    private $_pem;

    /**
     * @var Receipt
     */
    private $_receipt;

    /**
     * @var string
     */
    private $_sdkIdentifier;

    /**
     * YotiClient constructor.
     *
     * @param string $sdkId SDK Id from dashboard (not to be mistaken for App ID)
     * @param string $pem can be passed in as contents of pem file or file://<file> format or actual path
     * @param string $connectApi
     * @param string $sdkIdentifier
     *
     * @throws \Exception
     */
    public function __construct($sdkId, $pem, $connectApi = self::DEFAULT_CONNECT_API, $sdkIdentifier = 'PHP')
    {
        $this->checkRequiredModules();

        $this->checkSdkId($sdkId);

        $this->processPem($pem);

        // Validate and set X-Yoti-SDK header value
        if($this->isValidSdkIdentifier($sdkIdentifier)) {
            $this->_sdkIdentifier = $sdkIdentifier;
        }

        $this->_sdkId = $sdkId;
        $this->_pem = $pem;
        $this->_connectApi = $connectApi;
    }

    /**
     * Get login url.
     *
     * @param string $appId
     *
     * @return string
     */
    public static function getLoginUrl($appId)
    {
        return self::CONNECT_BASE_URL . "/$appId";
    }

    /**
     * @return string|null
     */
    public function getOutcome()
    {
        return $this->_receipt->getSharingOutcome();
    }

    /**
     * Return Yoti user profile.
     *
     * @param null|string $encryptedConnectToken
     *
     * @return ActivityDetails
     *
     * @throws ActivityDetailsException
     * @throws Exception\ReceiptException
     */
    public function getActivityDetails($encryptedConnectToken = NULL)
    {
        if(!$encryptedConnectToken && array_key_exists('token', $_GET))
        {
            $encryptedConnectToken = $_GET['token'];
        }

        $this->_receipt = $this->getReceipt($encryptedConnectToken);

        // Check response was successful
        if($this->getOutcome() !== self::OUTCOME_SUCCESS)
        {
            throw new ActivityDetailsException('Outcome was unsuccessful', 502);
        }

        return new ActivityDetails($this->_receipt, $this->_pem);
    }

    /**
     * Perform AML profile check.
     *
     * @param \Yoti\Entity\AmlProfile $amlProfile
     *
     * @return \Yoti\Http\AmlResult
     *
     * @throws \Yoti\Exception\AmlException
     * @throws \Exception
     */
    public function performAmlCheck(AmlProfile $amlProfile)
    {
        // Get payload data from amlProfile
        $amlPayload     = new Payload($amlProfile->getData());
        // AML check endpoint
        $amlCheckEndpoint = self::AML_CHECK_ENDPOINT;

        // Initiate signedRequest
        $signedRequest  = new SignedRequest(
            $amlPayload,
            $amlCheckEndpoint,
            $this->_pem,
            $this->_sdkId,
            RestRequest::METHOD_POST
        );

        $result = $this->makeRequest($signedRequest, $amlPayload, RestRequest::METHOD_POST);

        // Get response data array
        $responseArr = json_decode($result['response'], TRUE);
        // Check if there is a JSON decode error
        $this->checkJsonError();

        // Validate result
        $this->validateResult($responseArr, $result['http_code']);

        // Set and return result
        return new AmlResult($responseArr);
    }

    /**
     * Make REST request to Connect API.
     *
     * @param SignedRequest $signedRequest
     * @param Payload $payload
     * @param string $httpMethod
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function makeRequest(SignedRequest $signedRequest, Payload $payload, $httpMethod = 'GET')
    {
        $signedMessage = $signedRequest->getSignedMessage();

        // Get request httpHeaders
        $httpHeaders = $this->getRequestHeaders($signedMessage);

        $request = new RestRequest(
            $httpHeaders,
            $signedRequest->getApiRequestUrl($this->_connectApi),
            $payload,
            $httpMethod
        );

        // Make request
        return $request->exec();
    }

    /**
     * Handle request result.
     *
     * @param array $responseArr
     * @param int $httpCode
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function validateResult(array $responseArr, $httpCode)
    {
        $httpCode = (int) $httpCode;

        if($httpCode === 200)
        {
            // The request is successful - nothing to do
            return;
        }

        $errorMessage = $this->getErrorMessage($responseArr);
        $errorCode = isset($responseArr['code']) ? $responseArr['code'] : 'Error';

        // Throw the error message that's included in the response
        if(!empty($errorMessage))
        {
            throw new AmlException("$errorCode - {$errorMessage}", $httpCode);
        }

        // Throw a general error message
        throw new AmlException("{$errorCode} - Server responded with {$httpCode}", $httpCode);
    }

    /**
     * Get error message from the response array.
     *
     * @param array $result
     *
     * @return null|string
     */
    public function getErrorMessage(array $result)
    {
        return isset($result['errors'][0]['message']) ? $result['errors'][0]['message'] : '';
    }

    /**
     * Get request httpHeaders.
     *
     * @param $signedMessage
     *
     * @return array
     *
     * @throws \Exception
     */
    private function getRequestHeaders($signedMessage)
    {
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
            self::YOTI_SDK_HEADER . ": {$this->_sdkIdentifier}",
            'Content-Type: application/json',
            'Accept: application/json',
        ];
    }

    /**
     * Decrypt and return receipt data.
     *
     * @param string $encryptedConnectToken
     * @param string $httpMethod
     *
     * @return Receipt
     *
     * @throws ActivityDetailsException
     * @throws Exception\ReceiptException
     */
    private function getReceipt($encryptedConnectToken, $httpMethod = RestRequest::METHOD_GET)
    {
        // Decrypt connect token
        $token = $this->decryptConnectToken($encryptedConnectToken);
        if (!$token)
        {
            throw new ActivityDetailsException('Could not decrypt connect token.', 401);
        }

        // Get path for this endpoint
        $path = "/profile/{$token}";
        $payload = new Payload();

        // This will throw an exception if an error occurs
        $signedRequest = new SignedRequest(
            $payload,
            $path,
            $this->_pem,
            $this->_sdkId,
            $httpMethod
        );

        $result = $this->makeRequest($signedRequest, $payload);

        $responseArr = $this->processResult($result);
        $this->checkForReceipt($responseArr);

        return new Receipt($responseArr['receipt']);
    }

    /**
     * @param array $result
     *
     * @return mixed
     *
     * @throws ActivityDetailsException
     */
    private function processResult(array $result)
    {
        $this->checkResponseStatus($result['http_code']);

        // Get decoded response data
        $responseArr = json_decode($result['response'], TRUE);

        $this->checkJsonError();

        return $responseArr;
    }

    /**
     * @param array $response
     *
     * @throws ActivityDetailsException
     */
    private function checkForReceipt(array $responseArr)
    {
        // Check receipt is in response
        if(!array_key_exists('receipt', $responseArr))
        {
            throw new ReceiptException('Receipt not found in response', 502);
        }
    }

    /**
     * @param $httpCode
     *
     * @throws ActivityDetailsException
     */
    private function checkResponseStatus($httpCode)
    {
        $httpCode = (int) $httpCode;
        if ($httpCode !== 200)
        {
            throw new ActivityDetailsException("Server responded with {$httpCode}", $httpCode);
        }
    }

    /**
     * Check if any error occurs during JSON decode.
     *
     * @throws \Exception
     */
    private function checkJsonError()
    {
        if(json_last_error() !== JSON_ERROR_NONE)
        {
            throw new \Exception('JSON response was invalid', 502);
        }
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

    /**
     * Decrypt connect token.
     *
     * @param string $encryptedConnectToken
     *
     * @return mixed
     */
    private function decryptConnectToken($encryptedConnectToken)
    {
        $tok = base64_decode(strtr($encryptedConnectToken, '-_,', '+/='));
        openssl_private_decrypt($tok, $token, $this->_pem);

        return $token;
    }

    /**
     * Validate and return PEM content.
     *
     * @param string bool|$pem
     *
     * @throws \Exception
     */
    public function processPem(&$pem)
    {
        // Check PEM passed
        if(!$pem)
        {
            throw new \Exception('PEM file is required', 400);
        }

        // Check that file exists if user passed PEM as a local file path
        if(strpos($pem, 'file://') !== FALSE && !file_exists($pem))
        {
            throw new \Exception('PEM file was not found.', 400);
        }

        // If file exists grab the content
        if(file_exists($pem))
        {
            $pem = file_get_contents($pem);
        }

        // Check if key is valid
        if(!openssl_get_privatekey($pem))
        {
            throw new \Exception('PEM key is invalid', 400);
        }
    }

    /**
     * Check SDK ID is provided.
     *
     * @param string $sdkId
     *
     * @throws \Exception
     */
    public function checkSdkId($sdkId)
    {
        // Check SDK ID passed
        if(!$sdkId)
        {
            throw new \Exception('SDK ID is required', 400);
        }
    }

    /**
     * Check PHP required modules.
     *
     * @throws \Exception
     */
    public function checkRequiredModules()
    {
        $requiredModules = ['curl', 'json'];
        foreach($requiredModules as $mod)
        {
            if(!extension_loaded($mod))
            {
                throw new \Exception("PHP module '$mod' not installed", 501);
            }
        }
    }

    /**
     * Validate SDK identifier.
     *
     * @param $providedHeader
     *
     * @return bool
     *
     * @throws \Exception
     */
    private function isValidSdkIdentifier($providedHeader)
    {
        if(in_array($providedHeader, $this->acceptedSDKIdentifiers, TRUE)) {
            return TRUE;
        }

        throw new \Exception("Wrong Yoti SDK header value provided: {$providedHeader}", 406);
    }
}