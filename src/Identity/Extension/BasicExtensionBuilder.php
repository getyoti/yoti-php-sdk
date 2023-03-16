<?php

namespace Yoti\Identity\Extension;

class BasicExtensionBuilder implements ExtensionBuilderInterface
{
    private string $type;

    /**
     * @var mixed
     */
    private $content;

    /**
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

    public function build(): Extension
    {
        return new Extension($this->type, $this->content);
    }
}
