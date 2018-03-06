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

    /**
     * @return bool|null
     */
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

    /**
     * @return string|null
     */
    public function getVerifiedAge()
    {
        return $this->verifiedAge;
    }

    public function __toString()
    {
        return "{'result': {$this->result}, 'verifiedAge': {$this->verifiedAge}}";
    }
}