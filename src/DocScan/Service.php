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
     * @var PemFile
     */
    private $pemFile;

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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint('/sessions')
            ->withQueryParam('sdkId', $this->sdkId)
            ->withPayload(Payload::fromJsonData($sessionSpec))
            ->withHeader('Content-Type', 'application/json')
            ->withPemFile($this->pemFile)
            ->withPost()
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s', $sessionId))
            ->withQueryParam('sdkId', $this->sdkId)
            ->withPemFile($this->pemFile)
            ->withGet()
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s', $sessionId))
            ->withQueryParam('sdkId', $this->sdkId)
            ->withPemFile($this->pemFile)
            ->withMethod(Request::METHOD_DELETE)
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/media/%s/content', $sessionId, $mediaId))
            ->withQueryParam('sdkId', $this->sdkId)
            ->withPemFile($this->pemFile)
            ->withGet()
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/media/%s/content', $sessionId, $mediaId))
            ->withQueryParam('sdkId', $this->sdkId)
            ->withPemFile($this->pemFile)
            ->withMethod(Request::METHOD_DELETE)
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
            ->withPemFile($this->pemFile)
            ->withGet();

        if ($isStrictlyLatin) {
            $requestBuilder->withQueryParam('includeNonLatin', '1');
        }

        $response = $requestBuilder
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withQueryParam('sdkId', $this->sdkId)
            ->withEndpoint("sessions/$sessionId/resources/face-capture")
            ->withPemFile($this->pemFile)
            ->withPayload(Payload::fromJsonData($createFaceCaptureResourcePayload))
            ->withPost()
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
        $response = (new RequestBuilder($this->config))
            ->withMultipartBoundary(Config::YOTI_MULTIPART_BOUNDARY)
            ->withMultipartBinaryBody(
                "binary-content",
                $faceCaptureImagePayload->getImageContents(),
                $faceCaptureImagePayload->getImageContentType(),
                'face-capture-image'
            )
            ->withPemFile($this->pemFile)
            ->withBaseUrl($this->apiUrl)
            ->withQueryParam('sdkId', $this->sdkId)
            ->withEndpoint("/sessions/$sessionId/resources/face-capture/$resourceId/image")
            ->withPut()
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withEndpoint(sprintf('/sessions/%s/configuration', $sessionId))
            ->withQueryParam('sdkId', $this->sdkId)
            ->withPemFile($this->pemFile)
            ->withGet()
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withPemFile($this->pemFile)
            ->withEndpoint(sprintf('/sessions/%s/instructions', $sessionId))
            ->withPut()
            ->withPayload(Payload::fromJsonData($instructions))
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withPemFile($this->pemFile)
            ->withEndpoint(sprintf('/sessions/%s/instructions', $sessionId))
            ->withGet()
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withPemFile($this->pemFile)
            ->withEndpoint(sprintf('/sessions/%s/instructions/pdf', $sessionId))
            ->withGet()
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withPemFile($this->pemFile)
            ->withEndpoint(sprintf('/sessions/%s/instructions/contact-profile', $sessionId))
            ->withGet()
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
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->apiUrl)
            ->withPemFile($this->pemFile)
            ->withEndpoint(sprintf('/sessions/%s/instructions/email', $sessionId))
            ->withPost()
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
