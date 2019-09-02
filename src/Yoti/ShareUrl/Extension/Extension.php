<?php

namespace Yoti\ShareUrl\Extension;

use \Yoti\Util\Validation;

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->getType(),
            'content' => $this->getContent(),
        ];
    }

    /**
     * Return JSON string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
