<?php

namespace Yoti\Test\Profile\TestTools;

use Yoti\Profile\Attribute;
use Yoti\Profile\BaseProfile;

/**
 * Only for test. Don't use in PROD!
 */
class BaseProfileAdd extends BaseProfile
{
    public $attributes;

    public function addAttributeToList(Attribute $attribute)
    {
        $this->attributes = $this->getAttributesList();
        $this->attributes[] = $attribute;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
