<?php

namespace Yoti;

use Yoti\Entity\Receipt;
use Yoti\Exception\RequestException;
use Yoti\Exception\YotiClientException;
use Yoti\Http\Payload;
use Yoti\Http\AmlResult;
use Yoti\Entity\AmlProfile;
use Yoti\Http\Request;
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
     * @var string
     */
    private $_sdkIdentifier;

    /**
     * @var Request
     */
    private $_request;

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
        $this->validateSdkIdentifier($sdkIdentifier);

        $this->_sdkId = $sdkId;
        $this->_pem = $pem;
        $this->_connectApi = $connectApi;
        $this->_sdkIdentifier = $sdkIdentifier;

        $this->setRequest();
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

        $receipt = $this->getReceipt($encryptedConnectToken);

        // Check response was successful
        if($receipt->getSharingOutcome() !== self::OUTCOME_SUCCESS)
        {
            throw new ActivityDetailsException('Outcome was unsuccessful', 502);
        }

        return new ActivityDetails($receipt, $this->_pem);
    }

    /**
     * Perform AML profile check.
     *
     * @param AmlProfile $amlProfile
     *
     * @return AmlResult
     *
     * @throws AmlException
     * @throws RequestException
     */
    public function performAmlCheck(AmlProfile $amlProfile)
    {
        // Get payload data from amlProfile
        $amlPayload     = new Payload($amlProfile->getData());
        // AML check endpoint
        $amlCheckEndpoint = self::AML_CHECK_ENDPOINT;

        $result = $this->makeRequest($amlPayload, $amlCheckEndpoint, Request::METHOD_POST);

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
     * @param string $endpoint
     * @param Payload $payload
     * @param string $httpMethod
     *
     * @return array
     *
     * @throws RequestException
     */
    protected function makeRequest(Payload $payload, $endpoint, $httpMethod = 'GET')
    {
        return $this->_request->makeRequest($payload, $endpoint, $httpMethod);
    }

    /**
     * @throws RequestException
     */
    private function setRequest()
    {
        $this->_request = new \Yoti\Http\Request(
            $this->_connectApi,
            $this->_pem,
            $this->_sdkId,
            $this->_sdkIdentifier
        );
    }

    /**
     * Handle request result.
     *
     * @param array $responseArr
     * @param int $httpCode
     *
     * @throws AmlException
     */
    private function validateResult(array $responseArr, $httpCode)
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
    private function getErrorMessage(array $result)
    {
        return isset($result['errors'][0]['message']) ? $result['errors'][0]['message'] : '';
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
     * @throws ReceiptException
     * @throws RequestException
     */
    private function getReceipt($encryptedConnectToken, $httpMethod = Request::METHOD_GET)
    {
        // Decrypt connect token
        $token = $this->decryptConnectToken($encryptedConnectToken);
        if (!$token)
        {
            throw new ActivityDetailsException('Could not decrypt connect token.', 401);
        }

        // Get path for this endpoint
        $endpoint = "/profile/{$token}";
        $payload = new Payload();
        $result = $this->makeRequest($payload, $endpoint, $httpMethod);

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
     * @throws YotiClientException
     */
    private function checkJsonError()
    {
        if(json_last_error() !== JSON_ERROR_NONE)
        {
            throw new YotiClientException('JSON response was invalid', 502);
        }
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
     * @throws YotiClientException
     */
    private function processPem(&$pem)
    {
        // Check PEM passed
        if(!$pem)
        {
            throw new YotiClientException('PEM file is required', 400);
        }

        // Check that file exists if user passed PEM as a local file path
        if(strpos($pem, 'file://') !== FALSE && !file_exists($pem))
        {
            throw new YotiClientException('PEM file was not found.', 400);
        }

        // If file exists grab the content
        if(file_exists($pem))
        {
            $pem = file_get_contents($pem);
        }

        // Check if key is valid
        if(!openssl_get_privatekey($pem))
        {
            throw new YotiClientException('PEM key is invalid', 400);
        }
    }

    /**
     * Check SDK ID is provided.
     *
     * @param string $sdkId
     *
     * @throws YotiClientException
     */
    private function checkSdkId($sdkId)
    {
        // Check SDK ID passed
        if(!$sdkId)
        {
            throw new YotiClientException('SDK ID is required', 400);
        }
    }

    /**
     * Check PHP required modules.
     *
     * @throws YotiClientException
     */
    private function checkRequiredModules()
    {
        $requiredModules = ['curl', 'json'];
        foreach($requiredModules as $mod)
        {
            if(!extension_loaded($mod))
            {
                throw new YotiClientException("PHP module '$mod' not installed", 501);
            }
        }
    }

    /**
     * Validate SDK identifier.
     *
     * @param $sdkIdentifier
     *
     * @throws YotiClientException
     */
    private function validateSdkIdentifier($sdkIdentifier)
    {
        if (!in_array($sdkIdentifier, $this->acceptedSDKIdentifiers, TRUE)) {
            throw new YotiClientException("Wrong Yoti SDK identifier provided: {$sdkIdentifier}", 406);
        }
    }
}