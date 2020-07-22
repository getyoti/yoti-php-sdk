<?php

declare(strict_types=1);

namespace Yoti\Test\Util;

use Yoti\Exception\Base64Exception;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Base64;

/**
 * @coversDefaultClass \Yoti\Util\Base64
 */
class Base64Test extends TestCase
{
    /**
     * @covers ::urlEncode
     *
     * @dataProvider stringDataProvider
     */
    public function testUrlEncode($string)
    {
        $this->assertEquals(
            self::base64UrlEncode($string),
            Base64::urlEncode($string)
        );
    }

    /**
     * @covers ::urlDecode
     *
     * @dataProvider stringDataProvider
     */
    public function testUrlDecode($string)
    {
        $this->assertEquals(
            $string,
            Base64::urlDecode(self::base64UrlEncode($string))
        );
    }

    /**
     * @covers ::urlDecode
     */
    public function testUrlDecodeThrowBase64Exception()
    {
        $this->expectException(Base64Exception::class);
        $this->expectExceptionMessage('Base64 URL value could not be decoded');

        Base64::urlDecode(TestData::INVALID_YOTI_CONNECT_TOKEN);
    }

    /**
     * @covers ::urlDecode
     *
     * @dataProvider stringDataProvider
     */
    public function testUrlDecodeNonUrlBase64($string)
    {
        $this->assertEquals(
            $string,
            Base64::urlDecode(base64_encode($string))
        );
    }

    /**
     * @covers ::urlDecode
     * @covers ::urlEncode
     *
     * @dataProvider stringDataProvider
     */
    public function testUrlDecodeWithUrlEncode($string)
    {
        $this->assertEquals(
            $string,
            Base64::urlDecode(Base64::urlEncode($string))
        );
    }

    /**
     * @covers ::urlDecode
     *
     * @dataProvider base64UrlStringDataProvider
     */
    public function testUrlDecodeWithUrlCharacters($base64Url, $base64)
    {
        $this->assertEquals(
            $base64,
            base64_encode(Base64::urlDecode($base64Url))
        );
    }

    /**
     * Provides strings with varying lengths.
     *
     * @return array
     */
    public function stringDataProvider(): array
    {
        return [
            ['some-string'],
            ['some-unencoded-value'],
            ['some-longer-unencoded-value'],
        ];
    }

    /**
     * Provides base64 URL strings and their base64 equivalent.
     *
     * @return array
     */
    public function base64UrlStringDataProvider(): array
    {
        return [
            ['HT-sGfUaHj-rDA', 'HT+sGfUaHj+rDA=='],
            ['X_uuRoHwt9_B6g', 'X/uuRoHwt9/B6g=='],
        ];
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private static function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
