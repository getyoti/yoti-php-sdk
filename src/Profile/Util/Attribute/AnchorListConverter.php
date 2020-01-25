<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

class AnchorListConverter
{
    /**
     * @param \Traversable<\Yoti\Protobuf\Attrpubapi\Anchor> $protobufAnchors
     *
     * @return \Yoti\Profile\Attribute\Anchor[]
     */
    public static function convert(\Traversable $protobufAnchors): array
    {
        $yotiAnchors = [];

        foreach ($protobufAnchors as $protobufAnchor) {
            $yotiAnchors[] = AnchorConverter::convert($protobufAnchor);
        }

        return $yotiAnchors;
    }
}
