<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

use Traversable;

class AnchorListConverter
{
    /**
     * @param Traversable<\Yoti\Protobuf\Attrpubapi\Anchor> $anchorList
     *
     * @return array<string, array<int, mixed>>.
     */
    public static function convert(Traversable $anchorList): array
    {
        $yotiAnchorsMap = [];

        foreach ($anchorList as $protobufAnchor) {
            $parsedAnchor = AnchorConverter::convert($protobufAnchor);
            $yotiAnchorsMap[(string) $parsedAnchor['oid']][] = $parsedAnchor['yoti_anchor'];
        }

        return $yotiAnchorsMap;
    }
}
