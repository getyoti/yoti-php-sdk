<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Extension;

/**
 * Builder for Extension.
 */
class ExtensionBuilder
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $content;

    /**
     * @param string $type
     *
     * @return $this
     */
    public function withType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param mixed $content
     *
     * @return $this
     */
    public function withContent($content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Extension\Extension
     */
    public function build(): Extension
    {
        return new Extension($this->type, $this->content);
    }
}
