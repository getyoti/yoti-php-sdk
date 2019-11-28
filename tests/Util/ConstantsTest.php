<?php

namespace YotiTest\Util;

use YotiTest\TestCase;
use Yoti\Util\Constants;

/**
 * @coversDefaultClass \Yoti\Util\Constants
 */
class ConstantsTest extends TestCase
{
    /**
     * Check SDK_VERSION matches composer version.
     */
    public function testSDKVersionMatchesComposer()
    {
        $composerJson = json_decode(file_get_contents(__DIR__ . '/../../composer.json'));

        $this->assertEquals($composerJson->version, Constants::SDK_VERSION);
    }
}
