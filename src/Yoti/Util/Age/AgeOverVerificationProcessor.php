<?php
namespace Yoti\Util\Age;

class AgeOverVerificationProcessor extends AbstractAgeProcessor
{
    const AGE_RULE_PATTERN = '/^age_over:[1-9][0-9]*$/';

    public function getAgePattern()
    {
        return self::AGE_RULE_PATTERN;
    }
}