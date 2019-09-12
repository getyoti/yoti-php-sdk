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
     * @return \Yoti\ShareUrl\Extension\ExtensionBuilder
     */
    public function withType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param mixed $content
     *
     * @return \Yoti\ShareUrl\Extension\ExtensionBuilder
     */
    public function withContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Extension\Extension
     */
    public function build()
    {
        return new Extension($this->type, $this->content);
    }
}
