<?php

namespace Yoti\Entity;

class ApplicationProfile
{
    const ATTR_APPLICATION_LOGO = 'application_logo';
    const ATTR_APPLICATION_NAME = 'application_name';
    const ATTR_APPLICATION_URL = 'application_url';
    const ATTR_APPLICATION_RECEIPT_BG_COLOR = 'application_receipt_bgcolor';

    private $profileAttributes;

    public function __construct(array $profileAttributes)
    {
        $this->profileAttributes = $profileAttributes;
    }

    public function getApplicationName()
    {
        return $this->getAttribute(self::ATTR_APPLICATION_NAME);
    }

    public function getApplicationUrl()
    {
        return $this->getAttribute(self::ATTR_APPLICATION_URL);
    }

    public function getApplicationLogo()
    {
        return $this->getAttribute(self::ATTR_APPLICATION_LOGO);
    }

    public function getApplicationReceiptBgColor()
    {
        return $this->getAttribute(self::ATTR_APPLICATION_RECEIPT_BG_COLOR);
    }

    public function getAttribute($attributeName)
    {
        if (isset($this->profileAttributes[$attributeName])) {
            $attributeObj = $this->profileAttributes[$attributeName];
            return $attributeObj instanceof Attribute ? $attributeObj : NULL;
        }
        return NULL;
    }

    public function getAttributes()
    {
        return $this->profileAttributes;
    }
}