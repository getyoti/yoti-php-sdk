<?php

namespace Yoti\Util\Profile;

use Traversable;

class AnchorListConverter
{
    public static function convert(Traversable $anchorList)
    {
        $yotiAnchorsMap = [];

        foreach ($anchorList as $protobufAnchor) {
            if ($parsedAnchors = AnchorConverter::convertAnchors($protobufAnchor)) {
                $yotiAnchorsMap = array_merge_recursive($yotiAnchorsMap, $parsedAnchors);
            }
        }

        return $yotiAnchorsMap;
    }
}
