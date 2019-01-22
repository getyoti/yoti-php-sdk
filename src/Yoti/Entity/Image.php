<?php
namespace Yoti\Entity;

use Yoti\Exception\AttributeException;

/**
 * Image entity class.
 *
 * @package Yoti\Entity
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
     * @var array
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
    public function __construct($content, $imageExtension)
    {
        $this->content = $content;
        $this->mimeType = $this->imageTypeToMimeType(strtolower($imageExtension));
    }

    /**
     * @param $imageExtension
     *
     * @return string
     *
     * @throws AttributeException
     */
    private function imageTypeToMimeType($imageExtension)
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return null|string
     */
    public function getBase64Content()
    {
        $data = base64_encode($this->content);
        return "data:{$this->mimeType};base64,{$data}";
    }

    /**
     * @param $imageExtension
     *
     * @throws AttributeException
     */
    private function validateImageExtension($imageExtension)
    {
        if (!isset($this->imageTypeMap[$imageExtension])) {
            throw new AttributeException("Image extension {$imageExtension} not supported");
        }
    }

    /**
     * Return Image data.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }
}