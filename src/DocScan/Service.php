<?php

declare(strict_types=1);

namespace Yoti\DocScan;

use Psr\Http\Message\ResponseInterface;
use Yoti\Constants;
use Yoti\DocScan\Exception\DocScanException;
use Yoti\DocScan\Session\Create\CreateSessionResult;
use Yoti\DocScan\Session\Create\FaceCapture\CreateFaceCaptureResourcePayload;
use Yoti\DocScan\Session\Create\FaceCapture\UploadFaceCaptureImagePayload;
use Yoti\DocScan\Session\Create\SessionSpecification;
use Yoti\DocScan\Session\Instructions\Instructions;
use Yoti\DocScan\Session\Retrieve\Configuration\SessionConfigurationResponse;
use Yoti\DocScan\Session\Retrieve\CreateFaceCaptureResourceResponse;
use Yoti\DocScan\Session\Retrieve\GetSessionResult;
use Yoti\DocScan\Session\Retrieve\Instructions\ContactProfileResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\InstructionsResponse;
use Yoti\DocScan\Support\SupportedDocumentsResponse;
use Yoti\Http\AuthStrategy\AuthStrategyInterface;
use Yoti\Http\Payload;
use Yoti\Http\Request;
use Yoti\Http\RequestBuilder;
use Yoti\Media\Media;
use Yoti\Util\Config;
use Yoti\Util\Json;
use Yoti\Util\PemFile;

class Service
{
    /** @const int */
    private const HTTP_STATUS_NO_CONTENT = 204;

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

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @param string $sdkId
     * @param PemFile $pemFile
     * @param Config $config
     */
    public function __construct(string $sdkId, PemFile $pemFile, Config $config)
    {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->config = $config;
        $this->apiUrl = $config->getApiUrl() ?? Constants::DOC_SCAN_API_URL;
    }

    /**
     * Create a Service instance using an authentication strategy.
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
        $service->apiUrl = $config->getApiUrl() ?? Constants::DOC_SCAN_API_URL;
        $service->sdkId = '';
        return $service;
    }

    /**
     * Apply authentication to a RequestBuilder.
     *
     * If an explicit auth strategy was set, uses it.
     * Otherwise falls back to the legacy PemFile + sdkId approach.
     *
     * @param RequestBuilder $builder
     * @param bool $includeSdkId Whether to include sdkId as query param (legacy mode only)
     *
     * @return RequestBuilder
     */
    private function applyAuth(RequestBuilder $builder, bool $includeSdkId = true): RequestBuilder
    {
        if ($this->authStrategy !== null) {
            return $builder->withAuthStrategy($this->authStrategy);
        }

        $builder->withPemFile($this->pemFile);
        if ($includeSdkId && !empty($this->sdkId)) {
            $builder->withQueryParam('sdkId', $this->sdkId);
        }
        return $builder;
    }

    /**
     * Creates a Yoti Doc Scan session using the supplied
     * specification.
     *
     * @param SessionSpecification $sessionSpec
     *
     * @return CreateSessionResult
     *
     * @throws DocScanException
     */
    public function createSession(SessionSpecification $sessionSpec): CreateSessionResult
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint('/sessions')
            ->withPayload(Payload::fromJsonData($sessionSpec))
            ->withHeader('Content-Type', 'application/json')
            ->withPost();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);

        $result = Json::decode((string)$response->getBody());

        return new CreateSessionResult($result);
    }

    /**
     * Gets a session from the Yoti Doc Scan system.
     *
     * @param string $sessionId
     * @return GetSessionResult
     * @throws DocScanException
     */
    public function retrieveSession(string $sessionId): GetSessionResult
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s', $sessionId))
            ->withGet();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);

        $result = Json::decode((string)$response->getBody());

        return new GetSessionResult($result);
    }

    /**
     * Deletes a session from the Yoti Doc Scan system.
     *
     * @param string $sessionId
     * @throws DocScanException
     */
    public function deleteSession(string $sessionId): void
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s', $sessionId))
            ->withMethod(Request::METHOD_DELETE);

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);
    }

    /**
     * Retrieves media content from a Doc Scan session using supplied
     * media ID.
     *
     * @param string $sessionId
     * @param string $mediaId
     * @return Media|null if 204 No Content
     * @throws DocScanException
     */
    public function getMediaContent(string $sessionId, string $mediaId): ?Media
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/media/%s/content', $sessionId, $mediaId))
            ->withGet();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);

        if ($response->getStatusCode() == self::HTTP_STATUS_NO_CONTENT) {
            return null;
        }

        $content = (string)$response->getBody();
        $mimeType = $response->getHeader("Content-Type")[0] ?? '';

        return new Media($mimeType, $content);
    }

    /**
     * Deletes media from a Yoti Doc Scan session using
     * supplied media ID.
     *
     * @param string $sessionId
     * @param string $mediaId
     * @throws DocScanException
     */
    public function deleteMediaContent(string $sessionId, string $mediaId): void
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/media/%s/content', $sessionId, $mediaId))
            ->withMethod(Request::METHOD_DELETE);

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);
    }

    /**
     * @param bool $isStrictlyLatin
     * @return SupportedDocumentsResponse
     * @throws DocScanException
     */
    public function getSupportedDocuments(bool $isStrictlyLatin): SupportedDocumentsResponse
    {
        $requestBuilder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint('/supported-documents')
            ->withGet();

        if ($isStrictlyLatin) {
            $requestBuilder->withQueryParam('includeNonLatin', '1');
        }

        // getSupportedDocuments does not require sdkId in legacy mode
        $response = $this->applyAuth($requestBuilder, false)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);

        $result = Json::decode((string)$response->getBody());

        return new SupportedDocumentsResponse($result);
    }

    /**
     * @param string $sessionId
     * @param CreateFaceCaptureResourcePayload $createFaceCaptureResourcePayload
     * @return CreateFaceCaptureResourceResponse
     * @throws DocScanException
     */
    public function createFaceCaptureResource(
        string $sessionId,
        CreateFaceCaptureResourcePayload $createFaceCaptureResourcePayload
    ): CreateFaceCaptureResourceResponse {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint("sessions/$sessionId/resources/face-capture")
            ->withPayload(Payload::fromJsonData($createFaceCaptureResourcePayload))
            ->withPost();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);

        $result = Json::decode((string)$response->getBody());

        return new CreateFaceCaptureResourceResponse($result);
    }

    /**
     * @param string $sessionId
     * @param string $resourceId
     * @param UploadFaceCaptureImagePayload $faceCaptureImagePayload
     * @throws DocScanException
     */
    public function uploadFaceCaptureImage(
        string $sessionId,
        string $resourceId,
        UploadFaceCaptureImagePayload $faceCaptureImagePayload
    ): void {
        $builder = (new RequestBuilder($this->config))
            ->withMultipartBoundary(Config::YOTI_MULTIPART_BOUNDARY)
            ->withMultipartBinaryBody(
                "binary-content",
                $faceCaptureImagePayload->getImageContents(),
                $faceCaptureImagePayload->getImageContentType(),
                'face-capture-image'
            )
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint("/sessions/$sessionId/resources/face-capture/$resourceId/image")
            ->withPut();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);
    }

    /**
     * @param string $sessionId
     * @return SessionConfigurationResponse
     * @throws DocScanException
     */
    public function fetchSessionConfiguration(string $sessionId): SessionConfigurationResponse
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/configuration', $sessionId))
            ->withGet();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);

        $result = Json::decode((string)$response->getBody());

        return new SessionConfigurationResponse($result);
    }

    /**
     * @param string $sessionId
     * @param Instructions $instructions
     * @throws DocScanException
     */
    public function putIbvInstructions(string $sessionId, Instructions $instructions): void
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/instructions', $sessionId))
            ->withPut()
            ->withPayload(Payload::fromJsonData($instructions));

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);
    }

    /**
     * @param string $sessionId
     * @return InstructionsResponse
     * @throws DocScanException
     */
    public function getIbvInstructions(string $sessionId): InstructionsResponse
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/instructions', $sessionId))
            ->withGet();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);

        $result = Json::decode((string)$response->getBody());

        return new InstructionsResponse($result);
    }

    /**
     * @param string $sessionId
     * @return Media
     * @throws DocScanException
     */
    public function getIbvInstructionsPdf(string $sessionId): Media
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/instructions/pdf', $sessionId))
            ->withGet();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);

        $content = (string)$response->getBody();
        $mimeType = $response->getHeader("Content-Type")[0] ?? '';

        return new Media($mimeType, $content);
    }

    /**
     * @param string $sessionId
     * @return ContactProfileResponse
     * @throws DocScanException
     */
    public function fetchInstructionsContactProfile(string $sessionId): ContactProfileResponse
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/instructions/contact-profile', $sessionId))
            ->withGet();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);

        $result = Json::decode((string) $response->getBody());

        return new ContactProfileResponse($result);
    }

    /**
     * @param string $sessionId
     * @throws DocScanException
     */
    public function triggerIbvEmailNotification(string $sessionId): void
    {
        $builder = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/instructions/email', $sessionId))
            ->withPost();

        $response = $this->applyAuth($builder)
            ->build()
            ->execute();

        self::assertResponseIsSuccess($response);
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws DocScanException
     */
    private static function assertResponseIsSuccess(ResponseInterface $response): void
    {
        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new DocScanException("Server responded with {$httpCode}", $response);
        }
    }
}
