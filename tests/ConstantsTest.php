<?php

declare(strict_types=1);

namespace Yoti\Test;

use Yoti\Constants;

/**
 * @coversDefaultClass \Yoti\Constants
 */
class ConstantsTest extends TestCase
{
    /**
     * Check SDK_VERSION matches composer version.
     */
    public function testSDKVersionMatchesComposer()
    {
        $composerJson = json_decode(file_get_contents(__DIR__ . '/../composer.json'));

        $this->assertEquals($composerJson->version, Constants::SDK_VERSION);
    }
}
