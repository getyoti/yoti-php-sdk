<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

/**
 * Fluent builder for {@see SessionOverviewPdfOptions}.
 */
class SessionOverviewPdfOptionsBuilder
{
    /**
     * @var bool|null
     */
    private $includeImages;

    /**
     * @var bool|null
     */
    private $includeExtractedText;

    /**
     * @var bool|null
     */
    private $includeDeviceTracking;

    /**
     * @param bool $includeImages
     * @return $this
     */
    public function withIncludeImages(bool $includeImages): self
    {
        $this->includeImages = $includeImages;
        return $this;
    }

    /**
     * @param bool $includeExtractedText
     * @return $this
     */
    public function withIncludeExtractedText(bool $includeExtractedText): self
    {
        $this->includeExtractedText = $includeExtractedText;
        return $this;
    }

    /**
     * @param bool $includeDeviceTracking
     * @return $this
     */
    public function withIncludeDeviceTracking(bool $includeDeviceTracking): self
    {
        $this->includeDeviceTracking = $includeDeviceTracking;
        return $this;
    }

    /**
     * @return SessionOverviewPdfOptions
     */
    public function build(): SessionOverviewPdfOptions
    {
        return new SessionOverviewPdfOptions(
            $this->includeImages,
            $this->includeExtractedText,
            $this->includeDeviceTracking
        );
    }
}
