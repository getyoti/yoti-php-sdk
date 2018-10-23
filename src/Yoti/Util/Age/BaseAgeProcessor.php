<?php

namespace Yoti\Util\Age;

use Yoti\Entity\Attribute;

class BaseAgeProcessor implements AgeProcessorInterface
{
    // You must define this value in the child class
    const AGE_DELIMITER = '';

    // You must define this pattern in the child class
    const AGE_RULE_PATTERN = '';

    /**
     * @var Attribute
     */
    protected $attribute;

    /**
     * BaseAgeProcessor constructor.
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
        return preg_match(static::AGE_RULE_PATTERN, $attribute->getName()) ? true : false;
    }

    /**
     * This method could be overridden by a child class.
     * Depending on the parsing process and complexity.
     *
     * @param Attribute $attribute
     *
     * @return array
     */
    protected function extractAgeVerificationData(Attribute $attribute)
    {
        $ageCheckArr = explode(static::AGE_DELIMITER, $attribute->getName());
        if (count($ageCheckArr) > 1) {
            return [
                'checkType' => $ageCheckArr[0],
                'age' => (int) $ageCheckArr[1],
                'result' => $attribute->getValue() === 'true' ? true : false,
            ];
        }
        return NULL;
    }

    /**
     * Process derived attribute.
     *
     * @return array|null
     */
    public function process()
    {
        $resultArr = NULL;
        if ($this->isDerivedAttribute($this->attribute))
        {
            $resultArr = $this->extractAgeVerificationData($this->attribute);
        }
        return $resultArr;
    }
}