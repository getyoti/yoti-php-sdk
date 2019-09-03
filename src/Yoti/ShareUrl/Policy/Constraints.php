<?php

namespace Yoti\ShareUrl\Policy;

/**
 * List of constraints to apply to a wanted attribute.
 *
 * @class Constraints
 */
class Constraints extends \ArrayObject implements \JsonSerializable
{
    /**
     * @param array $constraints
     */
    public function __construct(array $constraints)
    {
        parent::__construct(array_values($constraints));
    }

    /**
     * @inheritDoc
     */
    public function append($value)
    {
        parent::append($value);
    }

    /**
     * @inheritDocs
     */
    public function exchangeArray($input)
    {
        parent::exchangeArray($input);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($index, $newval)
    {
        parent::offsetSet($index, $newval);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($index)
    {
        parent::offsetUnset($index);
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->getArrayCopy();
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
