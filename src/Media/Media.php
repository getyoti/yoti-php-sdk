<?php

declare(strict_types=1);

namespace Yoti\Media;

class Media
{
    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $content;

    /**
     * Media constructor.
     *
     * @param string $mimeType
     * @param string $content
     */
    public function __construct(string $mimeType, string $content)
    {
        $this->mimeType = $mimeType;
        $this->content = $content;
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
    public function getContent(): string
    {
        return $this->content;
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
     * Return Media data.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->content;
    }
}
