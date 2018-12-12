<?php

namespace YotiSandbox\Entity;

use Yoti\Entity\Profile;

class SandboxAgeVerification extends SandboxAttribute
{
    const AGE_OVER_FORMAT = 'age_over:%d';
    const AGE_UNDER_FORMAT = 'age_under:%d';

    public function __construct(\DateTime $dateObj, $derivation, array $anchors = [])
    {
        parent::__construct(
            Profile::ATTR_DATE_OF_BIRTH,
            $dateObj->format('Y-m-d'),
            $derivation,
            'true',
            $anchors
        );
    }

    public function setAgeOver($age)
    {
        $this->derivation = sprintf(self::AGE_OVER_FORMAT, $age);
    }

    public function setAgeUnder($age)
    {
        $this->derivation = sprintf(self::AGE_UNDER_FORMAT, $age);
    }
}