<?php

namespace YotiSandbox\Entity;

use Yoti\Entity\Attribute;

class SandboxAgeVerification extends SandboxAttribute
{
    public function __construct(\DateTime $dateObj, $derivation, array $anchors = [])
    {
        parent::__construct(
            Attribute::DATE_OF_BIRTH,
            $dateObj->format('Y-m-d'),
            $derivation,
            'true',
            $anchors
        );
    }
}