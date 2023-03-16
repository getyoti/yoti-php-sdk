<?php

declare(strict_types=1);

namespace Yoti;

use Yoti\Aml\Profile as AmlProfile;
use Yoti\Aml\Result as AmlResult;
use Yoti\Aml\Service as AmlService;
use Yoti\Exception\ActivityDetailsException;
use Yoti\Exception\IdentityException;
use Yoti\Exception\PemFileException;
use Yoti\Exception\ReceiptException;
use Yoti\Identity\IdentityService;
use Yoti\Identity\ShareSessionRequest;
use Yoti\Profile\ActivityDetails;
use Yoti\Profile\Service as ProfileService;
use Yoti\ShareUrl\DynamicScenario;
use Yoti\ShareUrl\Result as ShareUrlResult;
use Yoti\ShareUrl\Service as ShareUrlService;
use Yoti\Util\Config;
use Yoti\Util\Env;
use Yoti\Util\PemFile;
use Yoti\Util\Validation;

/**
 * Class YotiClient
 *
 * @package Yoti
 * @author Yoti SDK <websdk@yoti.com>
 */
class YotiClient
{
    private AmlService $amlService;

    private ProfileService $profileService;

    private ShareUrlService $shareUrlService;

    private IdentityService $identityService;

    /**
     * YotiClient constructor.
     *
     * @param string $sdkId
     *   The SDK identifier generated by Yoti Hub when you create your app.
     * @param string $pem
     *   PEM file path or string
     * @param array<string, mixed> $options (optional)
     *   SDK configuration options - {@see \Yoti\Util\Config} for available options.
     *
     * @throws PemFileException
     */
    public function __construct(
        string $sdkId,
        string $pem,
        array $options = []
    ) {
        Validation::notEmptyString($sdkId, 'SDK ID');
        $pemFile = PemFile::resolveFromString($pem);

        // Set API URL from environment variable.
        $options[Config::API_URL] = $options[Config::API_URL] ?? Env::get(Constants::ENV_API_URL);

        $config = new Config($options);

        $this->profileService = new ProfileService($sdkId, $pemFile, $config);
        $this->amlService = new AmlService($sdkId, $pemFile, $config);
        $this->shareUrlService = new ShareUrlService($sdkId, $pemFile, $config);
        $this->identityService = new IdentityService($sdkId, $pemFile, $config);
    }

    /**
     * Get login url.
     *
     * @param string $appId
     *
     * @return string
     */
    public static function getLoginUrl($appId): string
    {
        return Constants::CONNECT_BASE_URL . "/$appId";
    }

    /**
     * Return Yoti user profile.
     *
     * @param string $encryptedConnectToken
     *
     * @return ActivityDetails
     *
     * @throws ActivityDetailsException
     * @throws Exception\PemFileException
     * @throws ReceiptException
     */

    public function getActivityDetails(string $encryptedConnectToken): ActivityDetails
    {
        return $this->profileService->getActivityDetails($encryptedConnectToken);
    }

    /**
     * Perform AML profile check.
     *
     * @param AmlProfile $amlProfile
     *
     * @return AmlResult
     *
     * @throws Exception\AmlException
     */
    public function performAmlCheck(AmlProfile $amlProfile): AmlResult
    {
        return $this->amlService->performCheck($amlProfile);
    }

    /**
     * Get Share URL for provided dynamic scenario.
     *
     * @param DynamicScenario $dynamicScenario
     *
     * @return ShareUrlResult
     *
     * @throws Exception\ShareUrlException
     */
    public function createShareUrl(DynamicScenario $dynamicScenario): ShareUrlResult
    {
        return $this->shareUrlService->createShareUrl($dynamicScenario);
    }

    /**
     * Create a sharing session to initiate a sharing process based on a policy
     *
     * @throws IdentityException
     *
     * Aggregate exception signalling issues during the call
     */
    public function createShareSession(ShareSessionRequest $request): Identity\ShareSessionCreated
    {
        return $this->identityService->createShareSession($request);
    }

    /**
     * Create a sharing session QR code to initiate a sharing process based on a policy
     *
     * @throws IdentityException
     *
     * Aggregate exception signalling issues during the call
     */
    public function createShareQrCode(string $sessionId): Identity\ShareSessionCreatedQrCode
    {
        return $this->identityService->createShareQrCode($sessionId);
    }

    /**
     * Retrieve the sharing session QR code
     *
     * @throws IdentityException
     *
     * Aggregate exception signalling issues during the call
     */
    public function fetchShareQrCode(string $qrCodeId): Identity\ShareSessionFetchedQrCode
    {
        return $this->identityService->fetchShareQrCode($qrCodeId);
    }

    /**
     * Retrieve the sharing session
     *
     * @throws IdentityException
     *
     * Aggregate exception signalling issues during the call
     */
    public function fetchShareSession(string $sessionId): Identity\ShareSessionFetched
    {
        return $this->identityService->fetchShareSession($sessionId);
    }
}
