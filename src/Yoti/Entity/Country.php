<?php
namespace Yoti\Entity;

class Country
{
    private $name;

    private $code;

    public function __construct($name, $code = NULL)
    {
        $this->name = $name;
        $this->code = $code;
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