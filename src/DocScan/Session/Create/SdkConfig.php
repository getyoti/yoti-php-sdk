<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use Yoti\Util\Json;

class SdkConfig implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $allowedCaptureMethods;

    /**
     * @var string|null
     */
    private $primaryColour;

    /**
     * @var string|null
     */
    private $secondaryColour;

    /**
     * @var string|null
     */
    private $fontColour;

    /**
     * @var string|null
     */
    private $locale;

    /**
     * @var string|null
     */
    private $presetIssuingCountry;

    /**
     * @var string|null
     */
    private $successUrl;

    /**
     * @var string|null
     */
    private $errorUrl;

    /**
     * @var string|null
     */
    private $privacyPolicyUrl;

    /**
     * @var bool|null
     */
    private $allowHandoff;

    /**
     * @var AttemptsConfiguration|null
     */
    private $attemptsConfiguration;

    /**
     * @var string|null
     */
    private $biometricConsentFlow;

    /**
     * @param string|null $allowedCaptureMethods
     * @param string|null $primaryColour
     * @param string|null $secondaryColour
     * @param string|null $fontColour
     * @param string|null $locale
     * @param string|null $presetIssuingCountry
     * @param string|null $successUrl
     * @param string|null $errorUrl
     * @param string|null $privacyPolicyUrl
     * @param bool|null $allowHandoff
     * @param array<string, int>|null $idDocumentTextDataExtractionRetriesConfig
     * @param string|null $biometricConsentFlow
     */
    public function __construct(
        ?string $allowedCaptureMethods,
        ?string $primaryColour,
        ?string $secondaryColour,
        ?string $fontColour,
        ?string $locale,
        ?string $presetIssuingCountry,
        ?string $successUrl,
        ?string $errorUrl,
        ?string $privacyPolicyUrl = null,
        ?bool $allowHandoff = null,
        ?array $idDocumentTextDataExtractionRetriesConfig = null,
        ?string $biometricConsentFlow = null
    ) {
        $this->allowedCaptureMethods = $allowedCaptureMethods;
        $this->primaryColour = $primaryColour;
        $this->secondaryColour = $secondaryColour;
        $this->fontColour = $fontColour;
        $this->locale = $locale;
        $this->presetIssuingCountry = $presetIssuingCountry;
        $this->successUrl = $successUrl;
        $this->errorUrl = $errorUrl;
        $this->privacyPolicyUrl = $privacyPolicyUrl;
        $this->allowHandoff = $allowHandoff;
        if (!is_null($idDocumentTextDataExtractionRetriesConfig)) {
            $this->attemptsConfiguration = new AttemptsConfiguration($idDocumentTextDataExtractionRetriesConfig);
        }
        $this->biometricConsentFlow = $biometricConsentFlow;
    }
    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object)Json::withoutNullValues([
            'allowed_capture_methods' => $this->getAllowedCaptureMethods(),
            'primary_colour' => $this->getPrimaryColour(),
            'secondary_colour' => $this->getSecondaryColour(),
            'font_colour' => $this->getFontColour(),
            'locale' => $this->getLocale(),
            'preset_issuing_country' => $this->getPresetIssuingCountry(),
            'success_url' => $this->getSuccessUrl(),
            'error_url' => $this->getErrorUrl(),
            'privacy_policy_url' => $this->getPrivacyPolicyUrl(),
            'allow_handoff' => $this->getAllowHandoff(),
            'attempts_configuration' => $this->getAttemptsConfiguration(),
            'biometric_consent_flow' => $this->getBiometricConsentFlow()
        ]);
    }

    /**
     * @return string|null
     */
    public function getAllowedCaptureMethods(): ?string
    {
        return $this->allowedCaptureMethods;
    }

    /**
     * @return string|null
     */
    public function getPrimaryColour(): ?string
    {
        return $this->primaryColour;
    }

    /**
     * @return string|null
     */
    public function getSecondaryColour(): ?string
    {
        return $this->secondaryColour;
    }

    /**
     * @return string|null
     */
    public function getFontColour(): ?string
    {
        return $this->fontColour;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @return string|null
     */
    public function getPresetIssuingCountry(): ?string
    {
        return $this->presetIssuingCountry;
    }

    /**
     * @return string|null
     */
    public function getSuccessUrl(): ?string
    {
        return $this->successUrl;
    }

    /**
     * @return string|null
     */
    public function getErrorUrl(): ?string
    {
        return $this->errorUrl;
    }

    /**
     * @return string|null
     */
    public function getPrivacyPolicyUrl(): ?string
    {
        return $this->privacyPolicyUrl;
    }

    /**
     * @return bool|null
     */
    public function getAllowHandoff(): ?bool
    {
        return $this->allowHandoff;
    }

    /**
     * @return AttemptsConfiguration|null
     */
    public function getAttemptsConfiguration(): ?AttemptsConfiguration
    {
        return $this->attemptsConfiguration;
    }

     /**
     * @return string|null
     */
    public function getBiometricConsentFlow(): ?string
    {
        return $this->biometricConsentFlow;
    }
}
