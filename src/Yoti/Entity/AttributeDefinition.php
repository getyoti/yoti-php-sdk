<?php

namespace Yoti\Entity;

use Yoti\Util\Validation;

class AttributeDefinition implements \JsonSerializable
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        Validation::isString($name, 'name');
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
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
