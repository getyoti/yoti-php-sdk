<?php
namespace Yoti\Entity;

class Country
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var null|string
     */
    private $name;

    public function __construct($code, $name = NULL)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }
}