<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Profile\Attribute\Attribute;

class BaseProfile
{
    protected $profileData;

    /**
     * Profile constructor.
     *
     * @param array $profileData
     */
    public function __construct(array $profileData)
    {
        $this->profileData = $profileData;
    }

    /**
     * @param $attributeName.
     *
     * @return null|Attribute
     */
    public function getProfileAttribute(string $attributeName): ?Attribute
    {
        if (isset($this->profileData[$attributeName])) {
            $attributeObj = $this->profileData[$attributeName];
            return $attributeObj instanceof Attribute ? $attributeObj : null;
        }
        return null;
    }

    public function getAttributes(): array
    {
        $attributesMap = $this->profileData;
        // Remove age_verifications
        unset($attributesMap[Profile::ATTR_AGE_VERIFICATIONS]);
        return $attributesMap;
    }
}
