<?php

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
     * @return ExtensionBuilder
     */
    public function withType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param mixed $content
     *
     * @return ExtensionBuilder
     */
    public function withContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return Extension
     */
    public function build()
    {
        return new Extension($this->type, $this->content);
    }
}
