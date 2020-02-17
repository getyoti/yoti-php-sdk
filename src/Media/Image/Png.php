<?php

declare(strict_types=1);

namespace Yoti\Media\Image;

use Yoti\Media\Image;

class Png extends Image
{
    private const MIME_TYPE = 'image/png';

    public function __construct(string $content)
    {
        parent::__construct(self::MIME_TYPE, $content);
    }
}
