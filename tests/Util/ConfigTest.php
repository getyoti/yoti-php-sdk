<?php

namespace YotiTest\Util;

use YotiTest\TestCase;
use Yoti\Util\Config;

/**
 * @coversDefaultClass \Yoti\Util\Config
 */
class ConfigTest extends TestCase
{
    /**
     * @covers ::getInstance
     */
    public function testConfigInstance()
    {
        $this->assertInstanceOf(Config::class, Config::getInstance());
    }

    /**
     * @covers ::get
     */
    public function testGetSDKVersion()
    {
        $sdkVersion = Config::getInstance()->get('version');
        $this->assertNotNull($sdkVersion);
    }

    /**
     * @covers ::get
     */
    public function testSDKVersionMatchesComposer()
    {
        $configSdkVersion = Config::getInstance()->get('version');
        $composerJson = json_decode(file_get_contents(__DIR__ . '/../../composer.json'));

        $this->assertEquals($composerJson->version, $configSdkVersion);
    }
}
