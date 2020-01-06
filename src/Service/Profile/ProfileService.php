<?php

namespace Yoti\Service\Profile;

use Yoti\Entity\Receipt;
use Yoti\Exception\ActivityDetailsException;
use Yoti\Exception\ReceiptException;
use Yoti\Http\RequestBuilder;
use Yoti\Util\Config;
use Yoti\Util\Json;
use Yoti\Util\PemFile;

class ProfileService
{
    /** Request successful outcome */
    const OUTCOME_SUCCESS = 'SUCCESS';

    /** Auth HTTP header key */
    const YOTI_AUTH_HEADER_KEY = 'X-Yoti-Auth-Key';

    /**
     * @var \Yoti\Util\Config
     */
    private $config;

    /**
     * @param Yoti\Util\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Return Yoti user profile.
     *
     * @param string $encryptedConnectToken
     *
     * @return \Yoti\Service\Profile\ActivityDetails
     *
     * @throws \Yoti\Exception\ActivityDetailsException
     * @throws \Yoti\Exception\ReceiptException
     */
    public function getActivityDetails($encryptedConnectToken, PemFile $pemFile, string $sdkId): ActivityDetails
    {
        // Decrypt connect token
        $token = $this->decryptConnectToken($encryptedConnectToken, $pemFile);
        if (!$token) {
            throw new ActivityDetailsException('Could not decrypt connect token.');
        }

        // Request endpoint
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getConnectApiUrl())
            ->withEndpoint(sprintf('/profile/%s', $token))
            ->withQueryParam('appId', $sdkId)
            ->withHeader(self::YOTI_AUTH_HEADER_KEY, $pemFile->getAuthKey())
            ->withGet()
            ->withPemFile($pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new ActivityDetailsException("Server responded with {$httpCode}");
        }

        $result = Json::decode($response->getBody());

        $this->checkForReceipt($result);

        $receipt = new Receipt($result['receipt']);

        // Check response was successful
        if ($receipt->getSharingOutcome() !== self::OUTCOME_SUCCESS) {
            throw new ActivityDetailsException('Outcome was unsuccessful');
        }

        return new ActivityDetails($receipt, $pemFile);
    }

    /**
     * Decrypt connect token.
     *
     * @param string $encryptedConnectToken
     *
     * @return string|null
     */
    private function decryptConnectToken($encryptedConnectToken, PemFile $pemFile)
    {
        $tok = base64_decode(strtr($encryptedConnectToken, '-_,', '+/='));
        openssl_private_decrypt($tok, $token, (string) $pemFile);

        return $token;
    }

    /**
     * @param array $response
     *
     * @throws \Yoti\Exception\ReceiptException
     */
    private function checkForReceipt(array $responseArr)
    {
        // Check receipt is in response
        if (!array_key_exists('receipt', $responseArr)) {
            throw new ReceiptException('Receipt not found in response');
        }
    }
}
