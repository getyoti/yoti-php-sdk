<?php

namespace Yoti\ShareUrl\Extension;

use Yoti\Util\Validation;

/**
 * Defines Extension for Dynamic Scenario.
 */
class Extension implements \JsonSerializable
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
     * @param mixed $content
     */
    public function __construct($type, $content)
    {
        Validation::isString($type, 'type');
        $this->type = $type;

        Validation::notNull($type, 'content');
        $this->content = $content;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'content' => $this->content,
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
