<?php

namespace Yoti\Entity;

class ApplicationProfile extends BaseProfile
{
    const ATTR_APPLICATION_LOGO = 'application_logo';
    const ATTR_APPLICATION_NAME = 'application_name';
    const ATTR_APPLICATION_URL = 'application_url';
    const ATTR_APPLICATION_RECEIPT_BG_COLOR = 'application_receipt_bgcolor';

    public function getApplicationName()
    {
        return $this->getProfileAttribute(self::ATTR_APPLICATION_NAME);
    }

    public function getApplicationUrl()
    {
        return $this->getProfileAttribute(self::ATTR_APPLICATION_URL);
    }

    /**
     * The value will be of type Yoti\Entity\Image
     *
     * @return null|Attribute
     */
    public function getApplicationLogo()
    {
        return $this->getProfileAttribute(self::ATTR_APPLICATION_LOGO);
    }

    public function getApplicationReceiptBgColor()
    {
        return $this->getProfileAttribute(self::ATTR_APPLICATION_RECEIPT_BG_COLOR);
    }
}