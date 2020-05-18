<?php

declare(strict_types=1);

namespace Yoti\Test\Util;

use Yoti\Test\TestCase;
use Yoti\Util\Env;

/**
 * @coversDefaultClass \Yoti\Util\Env
 */
class EnvTest extends TestCase
{
    private const SOME_KEY = 'some-key';
    private const SOME_STRING = 'some-string';

    /**
     * @covers ::get
     * @backupGlobals enabled
     */
    public function testGet()
    {
        $_SERVER[self::SOME_KEY] = self::SOME_STRING;

        $this->assertEquals(
            self::SOME_STRING,
            Env::get(self::SOME_KEY)
        );
    }

    /**
     * @covers ::get
     * @backupGlobals enabled
     */
    public function testGetUnavailable()
    {
        $this->assertNull(Env::get(self::SOME_KEY));
    }
}
