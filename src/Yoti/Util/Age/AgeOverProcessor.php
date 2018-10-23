<?php
namespace Yoti\Util\Age;

class AgeOverProcessor extends BaseAgeProcessor
{
    const AGE_DELIMITER = ':';

    const AGE_RULE_PATTERN = '/^age_over:[1-9][0-9]*$/';
}