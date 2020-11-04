<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Constants;
use Yoti\Exception\ActivityDetailsException;
use Yoti\Exception\ReceiptException;
use Yoti\Http\RequestBuilder;
use Yoti\Util\Config;
use Yoti\Util\Json;
use Yoti\Util\PemFile;

class Service
{
    /** Request successful outcome */
    private const OUTCOME_SUCCESS = 'SUCCESS';

    /** Auth HTTP header key */
    private const YOTI_AUTH_HEADER_KEY = 'X-Yoti-Auth-Key';

    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var \Yoti\Util\Config
     */
    private $config;

    /**
     * @param string $sdkId
     * @param \Yoti\Util\PemFile $pemFile
     * @param \Yoti\Util\Config $config
     */
    public function __construct(string $sdkId, PemFile $pemFile, Config $config)
    {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->config = $config;
    }

    /**
     * Return Yoti user profile.
     *
     * @param string $encryptedConnectToken
     *
     * @return \Yoti\Profile\ActivityDetails
     *
     * @throws \Yoti\Exception\ActivityDetailsException
     * @throws \Yoti\Exception\ReceiptException
     */
    public function getActivityDetails(string $encryptedConnectToken): ActivityDetails
    {
        // Decrypt connect token
        $token = $this->decryptConnectToken($encryptedConnectToken);

        // Request endpoint
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::API_URL)
            ->withEndpoint(sprintf('/profile/%s', $token))
            ->withQueryParam('appId', $this->sdkId)
            ->withHeader(self::YOTI_AUTH_HEADER_KEY, $this->pemFile->getAuthKey())
            ->withGet()
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new ActivityDetailsException("Server responded with {$httpCode}");
        }

        $result = Json::decode((string) $response->getBody());

        $this->checkForReceipt($result);

        $receipt = new Receipt($result['receipt'], $this->config->getLogger());

        // Check response was successful
        if ($receipt->getSharingOutcome() !== self::OUTCOME_SUCCESS) {
            throw new ActivityDetailsException('Outcome was unsuccessful');
        }

        return new ActivityDetails($receipt, $this->pemFile, $this->config->getLogger());
    }

    /**
     * Decrypt connect token.
     *
     * @param string $encryptedConnectToken
     *
     * @return string
     */
    private function decryptConnectToken(string $encryptedConnectToken): string
    {
        $decodedToken = base64_decode(strtr($encryptedConnectToken, '-_', '+/'), true);
        if ($decodedToken === false) {
            throw new ActivityDetailsException('Could not decode one time use token.');
        }

        openssl_private_decrypt($decodedToken, $token, (string) $this->pemFile);

        if (!isset($token) || strlen($token) === 0) {
            throw new ActivityDetailsException('Could not decrypt one time use token.');
        }

        return $token;
    }

    /**
     * @param array<string, mixed> $responseArr
     *
     * @throws \Yoti\Exception\ReceiptException
     */
    private function checkForReceipt(array $responseArr): void
    {
        // Check receipt is in response
        if (!array_key_exists('receipt', $responseArr)) {
            throw new ReceiptException('Receipt not found in response');
        }
    }
}
