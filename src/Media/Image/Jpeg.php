<?php

declare(strict_types=1);

namespace Yoti\Media\Image;

use Yoti\Media\Image;

class Jpeg extends Image
{
    private const MIME_TYPE = 'image/jpeg';

    public function __construct(string $content)
    {
        parent::__construct(self::MIME_TYPE, $content);
    }
}
