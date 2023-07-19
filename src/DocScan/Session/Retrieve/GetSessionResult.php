<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Constants;
use Yoti\Util\DateTime;

class GetSessionResult
{
    /**
     * @var string|null
     */
    private $state;

    /**
     * @var CheckResponse[]
     */
    private $checks = [];

    /**
     * @var ResourceContainer|null
     */
    private $resources;

    /**
     * @var string|null
     */
    private $sessionId;

    /**
     * @var string|null
     */
    private $userTrackingId;

    /**
     * @var string|null
     */
    private $clientSessionToken;

    /**
     * @var int|null
     */
    private $clientSessionTokenTtl;

    /**
     * @var \DateTime
     */
    private $biometricConsent;

    private ?IdentityProfileResponse $identityProfile;

    private ?IdentityProfilePreviewResponse $identityProfilePreview;

    private ?ImportTokenResponse $importToken;

    /**
     * DocScanSession constructor.
     * @param array<string, mixed> $sessionData
     *
     * @throws \Yoti\Exception\DateTimeException
     */
    public function __construct(array $sessionData)
    {
        $this->state = $sessionData['state'] ?? null;
        $this->sessionId = $sessionData['session_id'] ?? null;
        $this->userTrackingId = $sessionData['user_tracking_id'] ?? null;
        $this->clientSessionToken = $sessionData['client_session_token'] ?? null;
        $this->clientSessionTokenTtl = $sessionData['client_session_token_ttl'] ?? null;

        if (isset($sessionData['biometric_consent'])) {
            $this->biometricConsent = DateTime::stringToDateTime($sessionData['biometric_consent']);
        }

        if (isset($sessionData['checks'])) {
            foreach ($sessionData['checks'] as $check) {
                $this->checks[] = $this->createCheckFromArray($check);
            }
        }

        if (isset($sessionData['resources'])) {
            $this->resources = new ResourceContainer($sessionData['resources']);
        }

        if (isset($sessionData['identity_profile'])) {
            $this->identityProfile = new IdentityProfileResponse($sessionData['identity_profile']);
        }

        if (isset($sessionData['identity_profile_preview'])) {
            $this->identityProfilePreview = new IdentityProfilePreviewResponse(
                $sessionData['identity_profile_preview']
            );
        }

        if (isset($sessionData['import_token'])) {
            $this->importToken = new ImportTokenResponse($sessionData['import_token']);
        }
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return CheckResponse[]
     */
    public function getChecks(): array
    {
        return $this->checks;
    }

    /**
     * @return ResourceContainer|null
     */
    public function getResources(): ?ResourceContainer
    {
        return $this->resources;
    }

    /**
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @return string|null
     */
    public function getUserTrackingId(): ?string
    {
        return $this->userTrackingId;
    }

    /**
     * @return string|null
     */
    public function getClientSessionToken(): ?string
    {
        return $this->clientSessionToken;
    }

    /**
     * @return int|null
     */
    public function getClientSessionTokenTtl(): ?int
    {
        return $this->clientSessionTokenTtl;
    }

    /**
     * @return \DateTime|null
     */
    public function getBiometricConsentTimestamp(): ?\DateTime
    {
        return $this->biometricConsent;
    }

    /**
     * @return AuthenticityCheckResponse[]
     */
    public function getAuthenticityChecks(): array
    {
        return $this->filterCheckByType(AuthenticityCheckResponse::class);
    }

    /**
     * @return IdDocumentComparisonCheckResponse[]
     */
    public function getIdDocumentComparisonChecks(): array
    {
        return $this->filterCheckByType(IdDocumentComparisonCheckResponse::class);
    }

    /**
     * @return ThirdPartyIdentityCheckResponse[]
     */
    public function getThirdPartyIdentityChecks(): array
    {
        return $this->filterCheckByType(ThirdPartyIdentityCheckResponse::class);
    }

    /**
     * @return WatchlistScreeningCheckResponse[]
     */
    public function getWatchlistScreeningChecks(): array
    {
        return $this->filterCheckByType(WatchlistScreeningCheckResponse::class);
    }

    /**
     * @return FaceMatchCheckResponse[]
     */
    public function getFaceMatchChecks(): array
    {
        return $this->filterCheckByType(FaceMatchCheckResponse::class);
    }

    /**
     * @return FaceComparisonCheckResponse[]
     */
    public function getFaceComparisonChecks(): array
    {
        return $this->filterCheckByType(FaceComparisonCheckResponse::class);
    }

    /**
     * @deprecated replaced by ::getIdDocumentTextDataChecks()
     *
     * @return TextDataCheckResponse[]
     */
    public function getTextDataChecks(): array
    {
        return $this->getIdDocumentTextDataChecks();
    }

    /**
     * @return TextDataCheckResponse[]
     */
    public function getIdDocumentTextDataChecks(): array
    {
        return $this->filterCheckByType(TextDataCheckResponse::class);
    }

    /**
     * @return SupplementaryDocTextDataCheckResponse[]
     */
    public function getSupplementaryDocumentTextDataChecks(): array
    {
        return $this->filterCheckByType(SupplementaryDocTextDataCheckResponse::class);
    }

    /**
     * @return mixed[]
     */
    public function getWatchlistAdvancedCaChecks(): array
    {
        return $this->filterCheckByType(WatchlistAdvancedCaCheckResponse::class);
    }

    /**
     * @return LivenessCheckResponse[]
     */
    public function getLivenessChecks(): array
    {
        return $this->filterCheckByType(LivenessCheckResponse::class);
    }

    /**
     * @return ThirdPartyIdentityFraudOneCheckResponse[]
     */
    public function getThirdPartyIdentityFraudOneChecks(): array
    {
        return $this->filterCheckByType(ThirdPartyIdentityFraudOneCheckResponse::class);
    }

    /**
     * @param array<string, mixed> $check
     * @return CheckResponse
     * @throws \Yoti\Exception\DateTimeException
     */
    private function createCheckFromArray(array $check): CheckResponse
    {
        switch ($check['type'] ?? null) {
            case Constants::ID_DOCUMENT_AUTHENTICITY:
                return new AuthenticityCheckResponse($check);
            case Constants::ID_DOCUMENT_COMPARISON:
                return new IdDocumentComparisonCheckResponse($check);
            case Constants::ID_DOCUMENT_FACE_MATCH:
                return new FaceMatchCheckResponse($check);
            case Constants::FACE_COMPARISON:
                return new FaceComparisonCheckResponse($check);
            case Constants::ID_DOCUMENT_TEXT_DATA_CHECK:
                return new TextDataCheckResponse($check);
            case Constants::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK:
                return new SupplementaryDocTextDataCheckResponse($check);
            case Constants::LIVENESS:
                return new LivenessCheckResponse($check);
            case Constants::THIRD_PARTY_IDENTITY:
                return new ThirdPartyIdentityCheckResponse($check);
            case Constants::WATCHLIST_SCREENING:
                return new WatchlistScreeningCheckResponse($check);
            case Constants::WATCHLIST_ADVANCED_CA:
                return new WatchlistAdvancedCaCheckResponse($check);
            case Constants::THIRD_PARTY_IDENTITY_FRAUD_1:
                return new ThirdPartyIdentityFraudOneCheckResponse($check);
            default:
                return new CheckResponse($check);
        }
    }

    /**
     * @param string $class
     * @return mixed[]
     */
    private function filterCheckByType(string $class): array
    {
        $filtered = array_filter(
            $this->getChecks(),
            function ($checkResponse) use ($class): bool {
                return $checkResponse instanceof $class;
            }
        );

        return array_values($filtered);
    }

    public function getIdentityProfile(): ?IdentityProfileResponse
    {
        return $this->identityProfile;
    }

    public function getIdentityProfilePreview(): ?IdentityProfilePreviewResponse
    {
        return $this->identityProfilePreview;
    }

    public function getImportToken(): ?ImportTokenResponse
    {
        return $this->importToken;
    }
}
