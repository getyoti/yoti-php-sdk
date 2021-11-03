<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\RequiredDocumentResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\RequiredIdDocumentResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\RequiredSupplementaryDocumentResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\FaceCapture\RequiredFaceCaptureResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Liveness\RequiredLivenessResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Liveness\RequiredZoomLivenessResourceResponse;

class CaptureResponse
{
    /**
     * @var string
     */
    private $biometricConsent;

    /**
     * @var array<int,RequiredResourceResponse>
     */
    private $requiredResources;

    /**
     * Returns a String enum of the state of biometric consent
     *
     * return if biometric consent needs to be captured
     *
     * @return string
     */
    public function getBiometricConsent(): string
    {
        return $this->biometricConsent;
    }

    /**
     * Returns a list of required resources, in order to satisfy the sessions
     * requirements
     *
     * return the list of required resources
     *
     * @return RequiredResourceResponse[]
     */
    public function getRequiredResources(): array
    {
        return $this->requiredResources;
    }

    /**
     * Returns a list of all the document resource requirements (including ID and supplementary documents)
     *
     * @return array<int,RequiredDocumentResourceResponse>
     */
    public function getDocumentResourceRequirements(): array
    {
        return $this->filter(RequiredDocumentResourceResponse::class);
    }

    /**
     * Returns a list of all the ID document resource requirements
     *
     * @return array<int,RequiredIdDocumentResourceResponse>
     */
    public function getIdDocumentResourceRequirements(): array
    {
        return $this->filter(RequiredIdDocumentResourceResponse::class);
    }

    /**
     * Returns a list of all the supplementary document resource requirements
     *
     * @return array<int,RequiredSupplementaryDocumentResourceResponse>
     */
    public function getSupplementaryResourceRequirements(): array
    {
        return $this->filter(RequiredSupplementaryDocumentResourceResponse::class);
    }

    /**
     * Returns a list of all the liveness resource requirements
     *
     * @return array<int,RequiredLivenessResourceResponse>
     */
    public function getLivenessResourceRequirements(): array
    {
        return $this->filter(RequiredLivenessResourceResponse::class);
    }

    /**
     * Returns a list of all the zoom liveness resource requirements
     *
     * @return array<int,RequiredZoomLivenessResourceResponse>
     */
    public function getZoomLivenessResourceRequirements(): array
    {
        return $this->filter(RequiredZoomLivenessResourceResponse::class);
    }

    /**
     * Returns a list of all the Face Capture resource requirements
     *
     * @return array<int,RequiredFaceCaptureResourceResponse>
     */
    public function getFaceCaptureResourceRequirements(): array
    {
        return $this->filter(RequiredFaceCaptureResourceResponse::class);
    }


    /**
     * Filter by className
     *
     * @param string $className
     * @return array<int,mixed>
     */
    private function filter(string $className): array
    {
        $filtered = array_filter($this->getRequiredResources(), function ($value) use ($className): bool {
            return $value instanceof $className;
        });

        return array_values($filtered);
    }
}
