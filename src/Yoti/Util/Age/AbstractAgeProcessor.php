<?php

namespace Yoti\Util\Age;

use Yoti\Entity\Attribute;
use Yoti\Entity\AgeVerification;

abstract class AbstractAgeProcessor
{
    // You could re-define this value in the child class
    const AGE_DELIMITER = ':';

    /**
     * @var Attribute
     */
    protected $attribute;

    /**
     * AbstractAgeProcessor constructor.
     *
     * @param Attribute $attribute
     */
    public function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * Return true if attribute name matches the pattern.
     *
     * @param Attribute $attribute
     *
     * @return bool
     */
    protected function isDerivedAttribute(Attribute $attribute)
    {
        return preg_match($this->getAgePattern(), $attribute->getName()) ? true : false;
    }

    /**
     * This method could be overridden by a child class.
     * Depending on the parsing process and complexity.
     *
     * @param Attribute $attribute
     *
     * @return null|AgeVerification
     */
    protected function createAgeVerification(Attribute $attribute)
    {
        $ageCheckArr = explode(static::AGE_DELIMITER, $attribute->getName());
        if (count($ageCheckArr) > 1) {
            $result = $attribute->getValue() === 'true' ? true : false;
            $checkType = $ageCheckArr[0];
            $age = (int) $ageCheckArr[1];

            return new AgeVerification(
                $attribute,
                $checkType,
                $age,
                $result
            );
        }
        return NULL;
    }

    /**
     * Process derived attribute.
     *
     * @return AgeVerification|null
     */
    public function process()
    {
        if ($this->isDerivedAttribute($this->attribute))
        {
            return $this->createAgeVerification($this->attribute);
        }
        return NULL;
    }

    /**
     * Return Age rule pattern as a regex.
     *
     * @return string
     */
    public abstract function getAgePattern();
}