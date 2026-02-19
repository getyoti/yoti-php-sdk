<?php

namespace Yoti\Identity;

use Yoti\Constants;
use Yoti\Exception\DigitalIdentityException;
use Yoti\Http\AuthStrategy\AuthStrategyInterface;
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

    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var PemFile|null
     */
    private $pemFile;

    /**
     * @var AuthStrategyInterface|null
     */
    private $authStrategy;

    /**
     * @var Config
     */
    private $config;

    public function __construct(string $sdkId, PemFile $pemFile, Config $config)
    {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->config = $config;
    }

    /**
     * Create a DigitalIdentityService instance using an authentication strategy.
     *
     * When using BearerTokenStrategy (central auth), no sdkId or PEM
     * is required since the Bearer token handles authorization.
     *
     * @param AuthStrategyInterface $authStrategy
     * @param Config $config
     *
     * @return self
     */
    public static function withAuthStrategy(AuthStrategyInterface $authStrategy, Config $config): self
    {
        $instance = new \ReflectionClass(self::class);
        $service = $instance->newInstanceWithoutConstructor();
        $service->authStrategy = $authStrategy;
        $service->config = $config;
        $service->sdkId = '';
        return $service;
    }

    /**
     * Apply authentication to a RequestBuilder.
     *
     * If an explicit auth strategy was set, uses it directly.
     * Otherwise falls back to the legacy PemFile + X-Yoti-Auth-Id header approach.
     *
     * @param RequestBuilder $builder
     * @param bool $includeAuthId Whether to include X-Yoti-Auth-Id header (legacy mode only)
     *
     * @return RequestBuilder
     */
    private function applyAuth(RequestBuilder $builder, bool $includeAuthId = true): RequestBuilder
    {
        if ($this->authStrategy !== null) {
            return $builder->withAuthStrategy($this->authStrategy);
        }

        if ($this->pemFile !== null) {
            $builder->withPemFile($this->pemFile);
        }
        if ($includeAuthId && $this->sdkId !== null && $this->sdkId !== '') {
            $builder->withHeader('X-Yoti-Auth-Id', $this->sdkId);
        }
        return $builder;
    }

    public function createShareSession(ShareSessionRequest $shareSessionRequest): ShareSessionCreated
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(self::IDENTITY_SESSION_CREATION)
            ->withPost()
            ->withPayload(Payload::fromJsonData($shareSessionRequest));

        $response = $this->applyAuth($builder)
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
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(self::IDENTITY_SESSION_QR_CODE_CREATION, $sessionId))
            ->withPost();

        $response = $this->applyAuth($builder)
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
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(self::IDENTITY_SESSION_QR_CODE_RETRIEVAL, $qrCodeId))
            ->withGet();

        $response = $this->applyAuth($builder)
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
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(self::IDENTITY_SESSION_RETRIEVAL, $sessionId))
            ->withGet();

        $response = $this->applyAuth($builder)
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

            /** @var PemFile $pemFile */
            $pemFile = $this->pemFile;
            return $receiptParser->createSuccess($wrappedReceipt, $receiptKey, $pemFile);
        }

        return $receiptParser->createFailure($wrappedReceipt);
    }

    private function doFetchShareReceipt(string $receiptId): WrappedReceipt
    {
        $receiptIdUrl = strtr($receiptId, '+/', '-_');
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(self::IDENTITY_SESSION_RECEIPT_RETRIEVAL, $receiptIdUrl))
            ->withGet();

        $response = $this->applyAuth($builder)
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
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::DIGITAL_IDENTITY_API_URL)
            ->withEndpoint(sprintf(
                self::IDENTITY_SESSION_RECEIPT_KEY_RETRIEVAL,
                $wrappedReceipt->getWrappedItemKeyId()
            ))
            ->withGet();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new DigitalIdentityException("Server responded with {$httpCode}", $response);
        }

        return new ReceiptItemKey(Json::decode((string)$response->getBody()));
    }
}
