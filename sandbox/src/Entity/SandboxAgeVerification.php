<?php

declare(strict_types=1);

namespace YotiSandbox\Entity;

use Yoti\Profile\Profile;

class SandboxAgeVerification extends SandboxAttribute
{
    const AGE_OVER_FORMAT = 'age_over:%d';
    const AGE_UNDER_FORMAT = 'age_under:%d';

    /**
     * @param \DateTime $dateObj
     * @param string $derivation
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     */
    public function __construct(\DateTime $dateObj, string $derivation = '', array $anchors = [])
    {
        parent::__construct(
            Profile::ATTR_DATE_OF_BIRTH,
            $dateObj->format('Y-m-d'),
            $derivation,
            'true',
            $anchors
        );
    }

    public function setAgeOver(int $age): void
    {
        $this->derivation = sprintf(self::AGE_OVER_FORMAT, $age);
    }

    public function setAgeUnder(int $age): void
    {
        $this->derivation = sprintf(self::AGE_UNDER_FORMAT, $age);
    }
}
