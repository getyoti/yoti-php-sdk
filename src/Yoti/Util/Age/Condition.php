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
        if(!empty($this->result))
        {
            return $this->result === 'true';
        }

        return NULL;
    }

    /**
     * @param $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @param $verifiedAge
     */
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

    /**
     * Returns a string representing this object.
     *
     * @return string
     */
    public function __toString()
    {
        return "{'result': {$this->result}, 'verifiedAge': {$this->verifiedAge}}";
    }
}