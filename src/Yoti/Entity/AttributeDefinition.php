<?php

namespace Yoti\Entity;

use Yoti\Util\Validation;

class AttributeDefinition
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
}
