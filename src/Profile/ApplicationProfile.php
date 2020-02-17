<?php

declare(strict_types=1);

namespace Yoti\Profile;

/**
 * Profile of an application with convenience methods to access well-known attributes.
 */
class ApplicationProfile extends BaseProfile
{
    public const ATTR_APPLICATION_LOGO = 'application_logo';
    public const ATTR_APPLICATION_NAME = 'application_name';
    public const ATTR_APPLICATION_URL = 'application_url';
    public const ATTR_APPLICATION_RECEIPT_BG_COLOR = 'application_receipt_bgcolor';

    /**
     * The name of the application.
     *
     * @return null|Attribute
     */
    public function getApplicationName(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_APPLICATION_NAME);
    }

    /**
     * The URL where the application is available at.
     *
     * @return null|Attribute
     */
    public function getApplicationUrl(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_APPLICATION_URL);
    }

    /**
     * The logo of the application that will be displayed to users that perform a share with it.
     *
     * @return null|Attribute
     *   The Attribute value will be of type Yoti\Media\Image
     */
    public function getApplicationLogo(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_APPLICATION_LOGO);
    }

    /**
     * The background colour that will be displayed on each receipt the user gets, as a result
     * of a share with the application.
     *
     * @return null|Attribute
     */
    public function getApplicationReceiptBgColor(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_APPLICATION_RECEIPT_BG_COLOR);
    }
}
