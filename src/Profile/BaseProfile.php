<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Profile\Attribute\Attribute;

class BaseProfile
{
    /**
     * @var mixed[]
     */
    protected $profileData;

    /**
     * Profile constructor.
     *
     * @param mixed[] $profileData
     */
    public function __construct(array $profileData)
    {
        $this->profileData = $profileData;
    }

    /**
     * @param string $attributeName.
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

    /**
     * @return \Yoti\Profile\Attribute\Attribute[]
     */
    public function getAttributes(): array
    {
        $attributesMap = $this->profileData;
        // Remove age_verifications
        unset($attributesMap[Profile::ATTR_AGE_VERIFICATIONS]);
        return $attributesMap;
    }
}
