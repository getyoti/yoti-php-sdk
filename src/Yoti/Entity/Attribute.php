<?php
namespace Yoti\Entity;

class Attribute
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $sources;

    /**
     * @var array
     */
    protected $verifiers;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param mixed $value
     * @param array $sources
     * @param array $verifiers
     */
    public function __construct($name, $value, array $sources, array $verifiers)
    {
        $this->name = $name;
        $this->value = $value;
        $this->sources = $sources;
        $this->verifiers = $verifiers;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * @return array
     */
    public function getVerifiers()
    {
        return $this->verifiers;
    }
}