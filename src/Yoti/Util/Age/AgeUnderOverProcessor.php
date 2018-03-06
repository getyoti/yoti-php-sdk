<?php

namespace Yoti\Util\Age;

class AgeUnderOverProcessor extends AbstractAgeProcessor
{
    const AGE_DELIMITER = ':';

    const AGE_PATTERN = '/^age_(under|over):[1-9][0-9]*$/';

    /**
     * Process profile data and extract age attribute and value.
     *
     * @return array|null
     */
    public function process()
    {
        $ageRow = $this->getAgeRow();

        if(!$ageRow) {
            return NULL;
        }

        $verifiedAge = $this->getVerifiedAge($ageRow['ageAttribute']);

        return ['result' => $ageRow['result'], 'verifiedAge' => $verifiedAge];
    }

    public function getVerifiedAge($ageAttribute)
    {
        $verifiedAge = '';
        $validationArr = explode(self::AGE_DELIMITER, $ageAttribute);

        if(count($validationArr) === 2) {
            list($attributePrefix, $age) = $validationArr;

            $ageIndicator = strpos($attributePrefix, 'under') !== FALSE ? 'under' : 'over';
            $verifiedAge = "{$ageIndicator} {$age}";
        }

        return $verifiedAge;
    }
}