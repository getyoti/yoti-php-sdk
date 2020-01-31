<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

class SdkConfigBuilder
{

    private const CAMERA = 'CAMERA';
    private const CAMERA_AND_UPLOAD = 'CAMERA_AND_UPLOAD';

    /**
     * @var string
     */
    private $allowedCaptureMethods;

    /**
     * @var string
     */
    private $primaryColour;

    /**
     * @var string
     */
    private $secondaryColour;

    /**
     * @var string
     */
    private $fontColour;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $presetIssuingCountry;

    /**
     * @var string
     */
    private $successUrl;

    /**
     * @var string
     */
    private $errorUrl;

    public function withAllowsCamera(): self
    {
        return $this->withAllowedCaptureMethod(self::CAMERA);
    }

    public function withAllowedCaptureMethod(string $allowedCaptureMethod): self
    {
        $this->allowedCaptureMethods = $allowedCaptureMethod;
        return $this;
    }

    public function withAllowsCameraAndUpload(): self
    {
        return $this->withAllowedCaptureMethod(self::CAMERA_AND_UPLOAD);
    }

    public function withPrimaryColour(string $primaryColour): self
    {
        $this->primaryColour = $primaryColour;
        return $this;
    }

    public function withSecondaryColour(string $secondaryColour): self
    {
        $this->secondaryColour = $secondaryColour;
        return $this;
    }

    public function withFontColour(string $fontColour): self
    {
        $this->fontColour = $fontColour;
        return $this;
    }

    public function withLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function withPresetIssuingCountry(string $presetIssuingCountry): self
    {
        $this->presetIssuingCountry = $presetIssuingCountry;
        return $this;
    }

    public function withSuccessUrl(string $successUrl): self
    {
        $this->successUrl = $successUrl;
        return $this;
    }

    public function withErrorUrl(string $errorUrl): self
    {
        $this->errorUrl = $errorUrl;
        return $this;
    }

    public function build(): SdkConfig
    {
        return new SdkConfig(
            $this->allowedCaptureMethods,
            $this->primaryColour,
            $this->secondaryColour,
            $this->fontColour,
            $this->locale,
            $this->presetIssuingCountry,
            $this->successUrl,
            $this->errorUrl
        );
    }
}
