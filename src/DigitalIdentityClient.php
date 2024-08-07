<?php

declare(strict_types=1);

namespace Yoti;

use Yoti\Exception\DigitalIdentityException;
use Yoti\Exception\PemFileException;
use Yoti\Identity\DigitalIdentityService;
use Yoti\Identity\ShareSessionRequest;
use Yoti\Util\Config;
use Yoti\Util\Env;
use Yoti\Util\PemFile;
use Yoti\Util\Validation;

/**
 * Class DigitalIdentityClient
 *
 * @package Yoti
 * @author Yoti SDK <websdk@yoti.com>
 */
class DigitalIdentityClient
{
    private DigitalIdentityService $digitalIdentityService;
    public string $id = '';
    /**
     * DigitalIdentityClient constructor.
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
        $options[Config::API_URL] = $options[Config::API_URL] ?? Env::get(Constants::ENV_DIGITAL_IDENTITY_API_URL);

        $config = new Config($options);

        $this->digitalIdentityService = new DigitalIdentityService($sdkId, $pemFile, $config);
        $this->id = $sdkId;
    }

    /**
     * Create a sharing session to initiate a sharing process based on a policy
     *
     * @throws DigitalIdentityException
     *
     * Aggregate exception signalling issues during the call
     */
    public function createShareSession(ShareSessionRequest $request): Identity\ShareSessionCreated
    {
        return $this->digitalIdentityService->createShareSession($request);
    }

    /**
     * Create a sharing session QR code to initiate a sharing process based on a policy
     *
     * @throws DigitalIdentityException
     *
     * Aggregate exception signalling issues during the call
     */
    public function createShareQrCode(string $sessionId): Identity\ShareSessionCreatedQrCode
    {
        return $this->digitalIdentityService->createShareQrCode($sessionId);
    }

    /**
     * Retrieve the sharing session QR code
     *
     * @throws DigitalIdentityException
     *
     * Aggregate exception signalling issues during the call
     */
    public function fetchShareQrCode(string $qrCodeId): Identity\ShareSessionFetchedQrCode
    {
        return $this->digitalIdentityService->fetchShareQrCode($qrCodeId);
    }

    /**
     * Retrieve the sharing session
     *
     * @throws DigitalIdentityException
     *
     * Aggregate exception signalling issues during the call
     */
    public function fetchShareSession(string $sessionId): Identity\ShareSessionFetched
    {
        return $this->digitalIdentityService->fetchShareSession($sessionId);
    }

    /**
     *  Retrieve the decrypted share receipt.
     *
     * @throws DigitalIdentityException
     *
     * Aggregate exception signalling issues during the call
     */
    public function fetchShareReceipt(string $receiptId): Identity\Receipt
    {
        return $this->digitalIdentityService->fetchShareReceipt($receiptId);
    }

    public function getSdkID(): string
    {
        return $this->id;
    }
}
