<?php

namespace Yoti\Helper;

use Yoti\Entity\Attribute;

class ProfileHelper
{
    /**
     * Return attribute source anchor values separated by the separator specified.
     *
     * @param null $attribute
     * @param string $separator
     *
     * @return string
     */
    public static function getAttributeSources($attribute = NULL, $separator = '<br>') {
        $sources = '';
        if ($attribute instanceof Attribute) {
            $sourcesArr = $attribute->getSources();
            foreach ($sourcesArr as $anchor) {
                $value = $anchor->getValue();
                $sources = empty($sources) ? $value : $sources . $separator . $value;
            }
        }
        return $sources;
    }

    /**
     * Return attribute source anchor values separated by the separator specified.
     *
     * @param null $attribute
     * @param string $separator
     *
     * @return string
     */
    public static function getAttributeVerifiers($attribute = NULL, $separator = '<br>')
    {
        $verifiers = '';
        if ($attribute instanceof Attribute) {
            $verifiersArr = $attribute->getVerifiers();
            foreach ($verifiersArr as $anchor) {
                $value = $anchor->getValue();
                $verifiers = empty($verifiers) ? $value : $verifiers . $separator . $value;
            }
        }

        return $verifiers;
    }
}