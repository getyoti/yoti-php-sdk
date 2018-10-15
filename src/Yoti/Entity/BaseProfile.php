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
        return $this->profileData;
    }
}