<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;

class SdkConfig implements JsonSerializable
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

    public function __construct(
        ?string $allowedCaptureMethods,
        ?string $primaryColour,
        ?string $secondaryColour,
        ?string $fontColour,
        ?string $locale,
        ?string $presetIssuingCountry,
        ?string $successUrl,
        ?string $errorUrl
    ) {
        $this->allowedCaptureMethods = $allowedCaptureMethods;
        $this->primaryColour = $primaryColour;
        $this->secondaryColour = $secondaryColour;
        $this->fontColour = $fontColour;
        $this->locale = $locale;
        $this->presetIssuingCountry = $presetIssuingCountry;
        $this->successUrl = $successUrl;
        $this->errorUrl = $errorUrl;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'allowed_capture_methods' => $this->getAllowedCaptureMethods(),
            'primary_colour' => $this->getPrimaryColour(),
            'secondary_colour' => $this->getSecondaryColour(),
            'font_colour' => $this->getFontColour(),
            'locale' => $this->getLocale(),
            'preset_issuing_country' => $this->getPresetIssuingCountry(),
            'success_url' => $this->getSuccessUrl(),
            'error_url' => $this->getErrorUrl(),
        ];
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
}
