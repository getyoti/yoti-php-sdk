<?php

namespace Yoti\Util\Age;

abstract class AbstractAgeProcessor
{
    const AGE_PATTERN = '';

    public $profileData;

    public function __construct(array $profileData)
    {
        $this->profileData = $profileData;
    }

    /**
     * Get age attribute and value.
     *
     * @return null|array
     */
    public function getAgeRow()
    {
        $resultArr = NULL;

        foreach($this->profileData as $key => $value)
        {
            if(preg_match(static::AGE_PATTERN, $key, $match))
            {
                $resultArr['ageAttribute'] = $match[0];
                $resultArr['result'] = $value;

                break;
            }
        }

        return $resultArr;
    }

    abstract public function process();
}