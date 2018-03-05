<?php

namespace Yoti\Util\Age;

class AgeUnderOverProcessor extends AbstractAgeProcessor
{
    private $pattern = '/^age_(under|over):\d.*$/';
    const AGE_DELIMITER = ':';

    public function process()
    {
        $found = FALSE;
        $rawData = '';
        $result = '';

        foreach($this->profileData as $key => $value)
        {
            if(preg_match($this->pattern, $key, $match))
            {
                $rawData = $match[0];
                $result = $value;
                $found = TRUE;

                break;
            }
        }

        if(!$found) {
            return NULL;
        }

        $validationArr = explode(self::AGE_DELIMITER, $rawData);
        $verifiedAge = count($validationArr) === 2 ? $validationArr[1] : '';

        return ['result' => $result, 'verifiedAge' => $verifiedAge];
    }
}