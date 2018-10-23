<?php
namespace Yoti\Util\Age;

class AgeUnderProcessor extends BaseAgeProcessor
{
    const AGE_DELIMITER = ':';

    const AGE_RULE_PATTERN = '/^age_under:[1-9][0-9]*$/';
}