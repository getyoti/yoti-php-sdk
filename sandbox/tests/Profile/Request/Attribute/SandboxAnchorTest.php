<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile\Request\Attribute;

use Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor
 */
class SandboxAnchorTest extends TestCase
{
    private const SOME_TYPE = 'SOURCE';
    private const SOME_VALUE = 'PASSPORT';
    private const SOME_SUB_TYPE = 'OCR';

    /**
     * @covers ::jsonSerialize
     * @covers ::__construct
     *
     * @dataProvider validTimestampProvider
     */
    public function testJsonSerialize($timestamp, $dateString)
    {
        $anchor = new SandboxAnchor(
            self::SOME_TYPE,
            self::SOME_VALUE,
            self::SOME_SUB_TYPE,
            new \DateTime($dateString)
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'type' => self::SOME_TYPE,
                'value' => self::SOME_VALUE,
                'sub_type' => self::SOME_SUB_TYPE,
                'timestamp' => $timestamp,
            ]),
            json_encode($anchor)
        );
    }

    /**
     * Provides valid microsecond timestamps and their expected RFC3339 representation with microseconds.
     */
    public function validTimestampProvider()
    {
        return [
            [ 1523538872835537, '2018-04-12T13:14:32.835537+00:00' ],
            [ -1571630945999999, '1920-03-13T19:50:54.000001+00:00' ],
            [ 1571630945999999, '2019-10-21T04:09:05.999999+00:00' ],
            [ 1571630945000000, '2019-10-21T04:09:05+00:00' ],
            [ 1571630945000000, '2019-10-21T04:09:05' ],
            [ 1571616000000000, '2019-10-21' ],
            [ -1571702400000000, '1920-03-13' ],
        ];
    }
}
