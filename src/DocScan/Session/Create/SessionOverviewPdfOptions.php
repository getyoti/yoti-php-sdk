<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;
use Yoti\Util\Json;

/**
 * Options controlling the content of a generated session overview PDF.
 *
 * All three flags are independently optional. Fields that have not been set
 * are omitted entirely from the serialised JSON so that the backend can apply
 * its own defaults.
 */
class SessionOverviewPdfOptions implements JsonSerializable
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
     * @param bool|null $includeImages
     * @param bool|null $includeExtractedText
     * @param bool|null $includeDeviceTracking
     */
    public function __construct(
        ?bool $includeImages = null,
        ?bool $includeExtractedText = null,
        ?bool $includeDeviceTracking = null
    ) {
        $this->includeImages = $includeImages;
        $this->includeExtractedText = $includeExtractedText;
        $this->includeDeviceTracking = $includeDeviceTracking;
    }

    /**
     * @return bool|null
     */
    public function getIncludeImages(): ?bool
    {
        return $this->includeImages;
    }

    /**
     * @return bool|null
     */
    public function getIncludeExtractedText(): ?bool
    {
        return $this->includeExtractedText;
    }

    /**
     * @return bool|null
     */
    public function getIncludeDeviceTracking(): ?bool
    {
        return $this->includeDeviceTracking;
    }

    /**
     * Serialises the options to a plain object, omitting any key whose value was not set.
     *
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) Json::withoutNullValues([
            'include_images' => $this->includeImages,
            'include_extracted_text' => $this->includeExtractedText,
            'include_device_tracking' => $this->includeDeviceTracking,
        ]);
    }
}
