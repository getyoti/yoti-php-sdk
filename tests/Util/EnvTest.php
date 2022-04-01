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
     *
     * @dataProvider envValueDataProvider
     */
    public function testGet($setValue, $getValue)
    {
        $_SERVER[self::SOME_KEY] = $setValue;

        $this->assertSame(
            $getValue,
            Env::get(self::SOME_KEY)
        );
    }

    /**
     * Provides environment variable values and their expected return values.
     */
    public function envValueDataProvider()
    {
        return [
            [ self::SOME_STRING, self::SOME_STRING ],
            [ '', null ],
            [ '0', '0' ],
            [ '1', '1' ],
            [ 0, '0' ],
            [ 1, '1' ],
            [ 'true', 'true' ],
            [ 'false', 'false' ],
            [ true, '1' ],
            [ false, '' ],
        ];
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
