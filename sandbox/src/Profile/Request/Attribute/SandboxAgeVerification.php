<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request\Attribute;

use Yoti\Profile\UserProfile;

class SandboxAgeVerification extends SandboxAttribute
{
    private const AGE_OVER_FORMAT = 'age_over:%d';
    private const AGE_UNDER_FORMAT = 'age_under:%d';

    /**
     * @param \DateTime $dateObj
     * @param string $derivation
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     */
    public function __construct(\DateTime $dateObj, string $derivation = '', array $anchors = [])
    {
        parent::__construct(
            UserProfile::ATTR_DATE_OF_BIRTH,
            $dateObj->format('Y-m-d'),
            $derivation,
            true,
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
