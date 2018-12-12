<?php

namespace YotiSandbox\Entity;

use Yoti\Entity\Profile;

class SandboxAgeVerification extends SandboxAttribute
{
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
}