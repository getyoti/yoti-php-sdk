<?php

namespace Yoti\Profile\Util\Attribute;

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
