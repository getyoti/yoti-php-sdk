<?php
namespace Yoti\Entity;

class Country
{
    /**
     * Country code.
     *
     * @var string
     */
    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @param $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}