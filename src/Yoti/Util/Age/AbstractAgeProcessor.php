<?php

namespace Yoti\Util\Age;

use Yoti\Entity\Attribute;

abstract class AbstractAgeProcessor
{
    // You must define this pattern in the child class
    const AGE_PATTERN = '';

    public $profileData;

    private $attribute;

    public function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;
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

    public function applyFilter()
    {
        if (preg_match(static::AGE_PATTERN, $this->attribute->getName(), $match))
        {
            return [
                'row_attribute' => $match[0],
                'result' => $this->attribute->getValue(),
                ];
        }
        return FALSE;
    }

    abstract public function process();

    abstract public function parseAttribute();
}