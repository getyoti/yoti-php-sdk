<?php

namespace Yoti\Util\Age;

class Condition
{
    private $result;
    private $verifiedAge;

    public function __construct($result = '', $verifiedAge = '')
    {
        $this->setResult($result);
        $this->setVerifiedAge($verifiedAge);
    }

    public function isVerified()
    {
        return empty(!$this->result) ? (bool) $this->result : NULL;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function setVerifiedAge($verifiedAge)
    {
        $this->verifiedAge = $verifiedAge;
    }

    public function getVerifiedAge()
    {
        return $this->verifiedAge;
    }
}