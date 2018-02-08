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

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}