<?php

namespace Yoti\Entity;

/**
 * Selfie entity class.
 *
 * @package Yoti\Entity
 */
class Selfie
{
    /**
     * Selfie image data.
     *
     * @var string
     */
    private $content;

    /**
     * Selfie image type.
     *
     * @var string
     */
    private $type;

    public function __construct($content, $type)
    {
        $this->content = $content;
        $this->type = strtolower($type);
    }

    /**
     * Set selfie image data.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        if(!empty($content)) {
            $this->content = $content;
        }
    }

    /**
     * Set selfie image type.
     *
     * @param string $type
     */
    public function setType($type)
    {
        if(!empty($type)) {
            $this->type = $type;
        }
    }

    /**
     * Returns selfie image data.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns selfie image type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return selfie data.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }
}