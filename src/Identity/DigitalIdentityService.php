<?php

namespace Yoti\Identity;

use Yoti\Constants;
use Yoti\Exception\DigitalIdentityException;
use Yoti\Http\Payload;
use Yoti\Http\RequestBuilder;
use Yoti\Util\Config;
use Yoti\Util\Json;
use Yoti\Util\PemFile;

class DigitalIdentityService
{
    private const IDENTITY_SESSION_CREATION = '/v2/sessions';
    private const IDENTITY_SESSION_RETRIEVAL = '/v2/sessions/%s';
    private const IDENTITY_SESSION_QR_CODE_CREATION = '/v2/sessions/%s/qr-codes';
    private const IDENTITY_SESSION_QR_CODE_RETRIEVAL = '/v2/qr-codes/%s';
    private const IDENTITY_SESSION_RECEIPT_RETRIEVAL = '/v2/receipts/%s';
    private const IDENTITY_SESSION_RECEIPT_KEY_RETRIEVAL = '/v2/wrapped-item-keys/%s';

    private string $sdkId;

    private PemFile $pemFile;

    private Config $config;

    public function __construct(string $sdkId, PemFile $pemFile, Config $config)
    {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->config = $config;
    }

    public function createShareSession(ShareSessionRequest $shareSessionRequest): ShareSessionCreated
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(self::IDENTITY_SESSION_CREATION)
            ->withHeader('X-Yoti-Auth-Id', $this->sdkId)
            ->withPost()
            ->withPayload(Payload::fromJsonData($shareSessionRequest))
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new DigitalIdentityException("Server responded with {$httpCode}", $response);
        }

        return new ShareSessionCreated(Json::decode((string)$response->getBody()));
    }

    public function createShareQrCode(string $sessionId): ShareSessionCreatedQrCode
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(self::IDENTITY_SESSION_QR_CODE_CREATION, $sessionId))
            ->withHeader('X-Yoti-Auth-Id', $this->sdkId)
            ->withPost()
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new DigitalIdentityException("Server responded with {$httpCode}", $response);
        }

        return new ShareSessionCreatedQrCode(Json::decode((string)$response->getBody()));
    }

    public function fetchShareQrCode(string $qrCodeId): ShareSessionFetchedQrCode
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(self::IDENTITY_SESSION_QR_CODE_RETRIEVAL, $qrCodeId))
            ->withHeader('X-Yoti-Auth-Id', $this->sdkId)
            ->withPost()
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new DigitalIdentityException("Server responded with {$httpCode}", $response);
        }

        return new ShareSessionFetchedQrCode(Json::decode((string)$response->getBody()));
    }

    public function fetchShareSession(string $sessionId): ShareSessionFetched
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(self::IDENTITY_SESSION_RETRIEVAL, $sessionId))
            ->withHeader('X-Yoti-Auth-Id', $this->sdkId)
            ->withPost()
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new DigitalIdentityException("Server responded with {$httpCode}", $response);
        }

        return new ShareSessionFetched(Json::decode((string)$response->getBody()));
    }

    /**
     * @throws DigitalIdentityException
     */
    public function fetchShareReceipt(string $receiptId): Receipt
    {
        $receiptParser = new ReceiptParser();
        $wrappedReceipt = $this->doFetchShareReceipt($receiptId);

        if (null === $wrappedReceipt->getError()) {
            $receiptKey = $this->fetchShareReceiptKey($wrappedReceipt);

            return $receiptParser->createSuccess($wrappedReceipt, $receiptKey, $this->pemFile);
        }

        return $receiptParser->createFailure($wrappedReceipt);
    }

    private function doFetchShareReceipt(string $receiptId): WrappedReceipt
    {
        $receiptIdUrl = strtr($receiptId, '+/', '-_');
        
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(self::IDENTITY_SESSION_RECEIPT_RETRIEVAL, $receiptIdUrl))
            ->withHeader('X-Yoti-Auth-Id', $this->sdkId)
            ->withGet()
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new DigitalIdentityException("Server responded with {$httpCode}", $response);
        }

        return new WrappedReceipt(Json::decode((string)$response->getBody()));
    }

    private function fetchShareReceiptKey(WrappedReceipt $wrappedReceipt): ReceiptItemKey
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(
                self::IDENTITY_SESSION_RECEIPT_KEY_RETRIEVAL,
                $wrappedReceipt->getWrappedItemKeyId()
            ))
            ->withHeader('X-Yoti-Auth-Id', $this->sdkId)
            ->withGet()
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new DigitalIdentityException("Server responded with {$httpCode}", $response);
        }

        return new ReceiptItemKey(Json::decode((string)$response->getBody()));
    }
}
