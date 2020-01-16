<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

use Traversable;

class AnchorListConverter
{
    public static function convert(Traversable $anchorList): array
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
