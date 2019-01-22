<?php

namespace Yoti\Entity;

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
    public function getProfileAttribute($attributeName)
    {
        if (isset($this->profileData[$attributeName])) {
            $attributeObj = $this->profileData[$attributeName];
            return $attributeObj instanceof Attribute ? $attributeObj : NULL;
        }
        return NULL;
    }

    public function getAttributes()
    {
        $attributesMap = $this->profileData;
        // Remove age_verifications
        unset($attributesMap[Profile::ATTR_AGE_VERIFICATIONS]);
        return $attributesMap;
    }
}