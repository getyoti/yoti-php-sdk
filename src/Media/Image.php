<?php

declare(strict_types=1);

namespace Yoti\Media;

use Yoti\Exception\AttributeException;
use Yoti\Media\Exception\InvalidImageTypeException;

/**
 * Image entity class.
 *
 * @package Yoti\Media
 */
class Image
{
    /**
     * Image data.
     *
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * Map image extension to the type enum which is an integer.
     *
     * @var array<string, int>
     */
    private $imageTypeMap = [
        'jpeg' => IMAGETYPE_JPEG,
        'png' => IMAGETYPE_PNG,
    ];

    /**
     * Image constructor.
     *
     * @param string $content
     * @param string $imageExtension
     */
    public function __construct(string $content, string $imageExtension)
    {
        $this->content = $content;
        $this->mimeType = $this->imageTypeToMimeType(strtolower($imageExtension));
    }

    /**
     * @param string $imageExtension
     *
     * @return string
     *
     * @throws AttributeException
     */
    private function imageTypeToMimeType(string $imageExtension): string
    {
        $this->validateImageExtension($imageExtension);
        $imageTypeEnum = $this->imageTypeMap[$imageExtension];

        return image_type_to_mime_type($imageTypeEnum);
    }

    /**
     * Returns Image data.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getBase64Content(): string
    {
        $data = base64_encode($this->content);
        return "data:{$this->mimeType};base64,{$data}";
    }

    /**
     * @param string $imageExtension
     *
     * @throws \Yoti\Exception\AttributeException
     */
    private function validateImageExtension(string $imageExtension): void
    {
        if (!isset($this->imageTypeMap[$imageExtension])) {
            throw new InvalidImageTypeException("{$imageExtension} extension not supported");
        }
    }

    /**
     * Return Image data.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->content;
    }
}
