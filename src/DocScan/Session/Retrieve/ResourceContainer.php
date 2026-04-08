<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class ResourceContainer
{
    /**
     * @var IdDocumentResourceResponse[]
     */
    private $idDocuments = [];

    /**
     * @var SupplementaryDocumentResourceResponse[]
     */
    private $supplementaryDocuments = [];

    /**
     * @var LivenessResourceResponse[]
     */
    private $livenessCapture = [];

    /**
     * @var FaceCaptureResourceResponse[]
     */
    private $faceCapture = [];

    /**
     * @var ShareCodeResourceResponse[]
     */
    private $shareCodes = [];

    /**
     * @var ApplicantProfileResourceResponse[]
     */
    private $applicantProfiles = [];

    /**
     * ResourceContainer constructor.
     * @param array<string, mixed> $resources
     */
    public function __construct(array $resources)
    {
        if (isset($resources['id_documents'])) {
            $this->idDocuments = $this->parseIdDocuments($resources['id_documents']);
        }

        if (isset($resources['supplementary_documents'])) {
            $this->supplementaryDocuments = $this->parseSupplementaryDocuments($resources['supplementary_documents']);
        }

        if (isset($resources['liveness_capture'])) {
            $this->livenessCapture = $this->parseLivenessCapture($resources['liveness_capture']);
        }

        if (isset($resources['face_capture'])) {
            $this->faceCapture = $this->parseFaceCapture($resources['face_capture']);
        }

        if (isset($resources['share_codes'])) {
            $this->shareCodes = $this->parseShareCodes($resources['share_codes']);
        }

        if (isset($resources['applicant_profiles'])) {
            $this->applicantProfiles = $this->parseApplicantProfiles($resources['applicant_profiles']);
        }
    }

    /**
     * @param array<array<string, mixed>> $idDocuments
     * @return IdDocumentResourceResponse[]
     */
    private function parseIdDocuments(array $idDocuments): array
    {
        $parsedIdDocuments = [];
        foreach ($idDocuments as $document) {
            $parsedIdDocuments[] = new IdDocumentResourceResponse($document);
        }
        return $parsedIdDocuments;
    }

    /**
     * @param array<array<string, mixed>> $supplementaryDocuments
     * @return SupplementaryDocumentResourceResponse[]
     */
    private function parseSupplementaryDocuments(array $supplementaryDocuments): array
    {
        $parsedSupplementaryDocuments = [];
        foreach ($supplementaryDocuments as $document) {
            $parsedSupplementaryDocuments[] = new SupplementaryDocumentResourceResponse($document);
        }
        return $parsedSupplementaryDocuments;
    }

    /**
     * @param array<array<string, mixed>> $livenessCaptures
     * @return LivenessResourceResponse[]
     */
    private function parseLivenessCapture(array $livenessCaptures): array
    {
        $parsedLivenessCaptures = [];
        foreach ($livenessCaptures as $capture) {
            if (isset($capture['liveness_type'])) {
                switch ($capture['liveness_type']) {
                    case 'ZOOM':
                        $parsedLivenessCaptures[] = new ZoomLivenessResourceResponse($capture);
                        break;
                    case 'STATIC':
                        $parsedLivenessCaptures[] = new StaticLivenessResourceResponse($capture);
                        break;
                    default:
                        $parsedLivenessCaptures[] = new LivenessResourceResponse($capture);
                        break;
                }
            }
        }
        return $parsedLivenessCaptures;
    }

    /**
     * @param array<array<string, mixed>> $faceCaptures
     * @return FaceCaptureResourceResponse[]
     */
    private function parseFaceCapture(array $faceCaptures): array
    {
        $parsedFaceCaptures = [];
        foreach ($faceCaptures as $faceCapture) {
            $parsedFaceCaptures[] = new FaceCaptureResourceResponse($faceCapture);
        }
        return $parsedFaceCaptures;
    }

    /**
     * @return IdDocumentResourceResponse[]
     */
    public function getIdDocuments(): array
    {
        return $this->idDocuments;
    }

    /**
     * @return SupplementaryDocumentResourceResponse[]
     */
    public function getSupplementaryDocuments(): array
    {
        return $this->supplementaryDocuments;
    }

    /**
     * @return LivenessResourceResponse[]
     */
    public function getLivenessCapture(): array
    {
        return $this->livenessCapture;
    }

    /**
     * @return ZoomLivenessResourceResponse[]
     */
    public function getZoomLivenessResources(): array
    {
        return $this->filterLivenessByType(ZoomLivenessResourceResponse::class);
    }

    /**
     * @return StaticLivenessResourceResponse[]
     */
    public function getStaticLivenessResources(): array
    {
        return $this->filterLivenessByType(StaticLivenessResourceResponse::class);
    }

    /**
     * @return FaceCaptureResourceResponse[]
     */
    public function getFaceCapture(): array
    {
        return $this->faceCapture;
    }

    /**
     * @return ShareCodeResourceResponse[]
     */
    public function getShareCodes(): array
    {
        return $this->shareCodes;
    }

    /**
     * @return ApplicantProfileResourceResponse[]
     */
    public function getApplicantProfiles(): array
    {
        return $this->applicantProfiles;
    }

    /**
     * @param array<array<string, mixed>> $shareCodes
     * @return ShareCodeResourceResponse[]
     */
    private function parseShareCodes(array $shareCodes): array
    {
        $parsedShareCodes = [];
        foreach ($shareCodes as $shareCode) {
            $parsedShareCodes[] = new ShareCodeResourceResponse($shareCode);
        }
        return $parsedShareCodes;
    }

    /**
     * @param array<array<string, mixed>> $applicantProfiles
     * @return ApplicantProfileResourceResponse[]
     */
    private function parseApplicantProfiles(array $applicantProfiles): array
    {
        $parsedApplicantProfiles = [];
        foreach ($applicantProfiles as $applicantProfile) {
            $parsedApplicantProfiles[] = new ApplicantProfileResourceResponse($applicantProfile);
        }
        return $parsedApplicantProfiles;
    }

    /**
     * @param string $class
     * @return mixed[]
     */
    private function filterLivenessByType(string $class): array
    {
        $filtered = array_filter(
            $this->getLivenessCapture(),
            function ($resourceResponse) use ($class): bool {
                return $resourceResponse instanceof $class;
            }
        );

        return array_values($filtered);
    }
}
