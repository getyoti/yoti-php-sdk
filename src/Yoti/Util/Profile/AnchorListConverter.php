<?php

namespace Yoti\Util\Profile;

use Traversable;

class AnchorListConverter
{
    public static function convert(Traversable $anchorList)
    {
        $yotiAnchorsMap = [];
        $anchorConverter = new AnchorConverter();

        foreach ($anchorList as $anchor) {
            $anchorConverter->convertToYotiAnchors(
                $anchor,
                $yotiAnchorsMap
            );
        }
        return $yotiAnchorsMap;
    }
}