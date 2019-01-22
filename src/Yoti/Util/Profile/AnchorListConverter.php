<?php

namespace Yoti\Util\Profile;

use Traversable;

class AnchorListConverter
{
    public static function convert(Traversable $anchorList)
    {
        $yotiAnchorsMap = [];

        foreach ($anchorList as $protobufAnchor) {
            if ($parsedAnchor = AnchorConverter::convert($protobufAnchor)) {
                $yotiAnchorsMap[$parsedAnchor['oid']][] = $parsedAnchor['yoti_anchor'];
            }
        }
        return $yotiAnchorsMap;
    }
}