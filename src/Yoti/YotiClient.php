<?php

namespace Yoti;

use compubapi_v1\EncryptedData;

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

    // Accepted HTTP header values for X-Yoti-SDK header
    const YOTI_ACCEPTED_SDK_IDENTIFIERS = [
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
     * @var array
     */
    private $_receipt;

    /**
     * @var bool
     */
    private $_mockRequests = false;

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
        $requiredModules = ['curl', 'json'];
        foreach ($requiredModules as $mod)
        {
            if (!extension_loaded($mod))
            {
                throw new \Exception("PHP module '$mod' not installed", 501);
            }
        }

        // Check sdk id passed
        if (!$sdkId)
        {
            throw new \Exception('SDK ID is required', 400);
        }

        // Check pem passed
        if (!$pem)
        {
            throw new \Exception('PEM file is required', 400);
        }

        // Check if user passed pem as file path rather than file contents
        if (strpos($pem, 'file://') === 0 || file_exists($pem))
        {
            if (!file_exists($pem))
            {
                throw new \Exception('PEM file was not found.', 400);
            }

            $pem = file_get_contents($pem);
        }

        // Check key is valid
        if (!openssl_get_privatekey($pem))
        {
            throw new \Exception('PEM key is invalid', 400);
        }

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
     * Set to test environment so it won't make requests to actual API.
     *
     * @param bool $toggle
     */
    public function setMockRequests($toggle = true)
    {
        $this->_mockRequests = $toggle;
    }

    /**
     * @return string|null
     */
    public function getOutcome()
    {
        return array_key_exists('sharing_outcome', $this->_receipt) ? $this->_receipt['sharing_outcome'] : null;
    }

    /**
     * Return Yoti user profile.
     *
     * @param string $encryptedConnectToken
     *
     * @return \Yoti\ActivityDetails
     *
     * @throws \Exception
     */
    public function getActivityDetails($encryptedConnectToken = null)
    {
        if (!$encryptedConnectToken && array_key_exists('token', $_GET))
        {
            $encryptedConnectToken = $_GET['token'];
        }

        $this->_receipt = $this->getReceipt($encryptedConnectToken);
        $encryptedData = $this->getEncryptedData($this->_receipt['other_party_profile_content']);

        // Check response was success
        if ($this->getOutcome() !== self::OUTCOME_SUCCESS)
        {
            throw new \Exception('Outcome was unsuccessful', 502);
        }

        // Set remember me Id
        $rememberMeId = array_key_exists('remember_me_id', $this->_receipt) ? $this->_receipt['remember_me_id'] : null;

        // If no profile return empty ActivityDetails object
        if (empty($this->_receipt['other_party_profile_content']))
        {
            return new ActivityDetails([], $rememberMeId);
        }

        // Decrypt attribute list
        $attributeList = $this->getAttributeList($encryptedData, $this->_receipt['wrapped_receipt_key']);

        // Get user profile
        return ActivityDetails::constructFromAttributeList($attributeList, $rememberMeId);
    }

    /**
     * Return Yoti dashboard endpoint.
     *
     * @param string $endpoint
     *
     * @return string
     */
    private function getEndpointPath($endpoint)
    {
        // Prepare message to sign
        $nonce = $this->generateNonce();
        $timestamp = round(microtime(true) * 1000);
        $path = "{$endpoint}?nonce={$nonce}&timestamp={$timestamp}&appId={$this->_sdkId}";

        return $path;
    }

    /**
     * Sign the message.
     *
     * @param string $message
     *
     * @return string
     */
    private function getSignedRequest($message)
    {
        openssl_sign($message, $signature, $this->_pem, OPENSSL_ALGO_SHA256);
        $messageSignature = base64_encode($signature);

        return $messageSignature;
    }

    /**
     * Decrypt and return receipt data.
     *
     * @param string $encryptedConnectToken
     *
     * @return array
     *
     * @throws \Exception
     */
    private function getReceipt($encryptedConnectToken)
    {
        // Decrypt connect token
        $token = $this->decryptConnectToken($encryptedConnectToken);
        if (!$token)
        {
            throw new \Exception('Could not connect decrypt token.', 401);
        }

        // Get path for this endpoint
        $path = $this->getEndpointPath("/profile/$token");

        // Sign the request
        $messageSignature = $this->getSignedRequest("GET&{$path}");
        if (!$messageSignature)
        {
            throw new \Exception('Could not sign request.', 401);
        }

        // Get auth key
        $authKey = $this->getAuthKeyFromPem();
        if (!$authKey)
        {
            throw new \Exception('Could not retrieve key from PEM.', 401);
        }

        // Build Url to hit
        $url = $this->_connectApi . $path;

        // Prepare request headers
        $headers = [
            "X-Yoti-Auth-Key: {$authKey}",
            "X-Yoti-Auth-Digest: {$messageSignature}",
            "X-Yoti-SDK: {$this->_sdkIdentifier}",
            "Content-Type: application/json",
            "Accept: application/json",
        ];

        // If !mockRequests then do the real thing
        if (!$this->_mockRequests)
        {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
            ]);
            $response = curl_exec($ch);

            // Check response code
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode !== 200)
            {
                $httpCode = (int) $httpCode;
                throw new \Exception("Server responded with {$httpCode}", $httpCode);
            }
        }
        else
        {
            // Sample receipt, don't make curl call instead spoof response from receipt.json
            $response = file_get_contents(__DIR__ . '/../sample-data/receipt.json');
        }

        // Get decoded response data
        $json = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE)
        {
            throw new \Exception('JSON response was invalid', 502);
        }

        // Check receipt is in response
        if (!array_key_exists('receipt', $json))
        {
            throw new \Exception('Receipt not found in response', 502);
        }

        return $json['receipt'];
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

    /**
     * @return null|string
     */
    private function getAuthKeyFromPem()
    {
        $details = openssl_pkey_get_details(openssl_pkey_get_private($this->_pem));
        if (!array_key_exists('key', $details))
        {
            return null;
        }

        // Remove BEGIN PUBLIC KEY / END PUBLIC KEY lines
        $key = trim($details['key']);
        $_key = explode(PHP_EOL, $key);
        if (strpos($key, 'BEGIN') !== false)
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
     * Return encrypted profile data.
     *
     * @param $profileContent
     *
     * @return \compubapi_v1\EncryptedData
     */
    private function getEncryptedData($profileContent)
    {
        // Get cipher_text and iv
        $encryptedData = new EncryptedData(base64_decode($profileContent));

        return $encryptedData;
    }

    /**
     * Return Yoti user profile attributes.
     *
     * @param EncryptedData $encryptedData
     * @param $wrappedReceiptKey
     *
     * @return \attrpubapi_v1\AttributeList
     */
    private function getAttributeList(EncryptedData $encryptedData, $wrappedReceiptKey)
    {
        // Unwrap key and get profile
        openssl_private_decrypt(base64_decode($wrappedReceiptKey), $unwrappedKey, $this->_pem);

        // Decipher encrypted data with unwrapped key and IV
        $cipherText = openssl_decrypt(
            $encryptedData->getCipherText(),
            'aes-256-cbc',
            $unwrappedKey,
            OPENSSL_RAW_DATA,
            $encryptedData->getIv()
        );

        $attributeList = new \attrpubapi_v1\AttributeList($cipherText);

        return $attributeList;
    }

    /**
     * Validate SDK identifier.
     *
     * @param $providedHeader
     *
     * @return bool
     * @throws \Exception
     */
    private function isValidSdkIdentifier($providedHeader)
    {
        if(in_array($providedHeader, self::YOTI_ACCEPTED_SDK_IDENTIFIERS, TRUE)) {
            return TRUE;
        }

        throw new \Exception("Wrong Yoti SDK header value provided: {$providedHeader}", 406);
    }
}