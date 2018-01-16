<?php

namespace Yoti\Entity;

class Selfie
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $type;

    public function __construct($content, $type)
    {
        $this->content = $content;
        $this->type = strtolower($type);
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        if(!empty($content)) {
            $this->content = $content;
        }
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
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