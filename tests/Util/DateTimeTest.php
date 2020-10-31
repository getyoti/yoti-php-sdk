<?php

declare(strict_types=1);

namespace Yoti\Test\Util;

use Yoti\Test\TestCase;
use Yoti\Util\DateTime;

/**
 * @coversDefaultClass \Yoti\Util\DateTime
 */
class DateTimeTest extends TestCase
{
    /**
     * @covers ::timestampToDateTime
     * @covers ::createFromFormat
     * @covers ::extractMicrosecondsFromTimestamp
     *
     * @dataProvider validTimestampProvider
     */
    public function testTimestampToDateTime($timestamp, $expectedDateString)
    {
        $this->assertEquals(
            $expectedDateString,
            DateTime::timestampToDateTime($timestamp)->format(DateTime::RFC3339)
        );
    }

    /**
     * @covers ::dateTimeToTimestamp
     *
     * @dataProvider validTimestampProvider
     */
    public function testDateTimeToTimestamp($timestamp, $expectedDateString)
    {
        $dateTime = new \DateTime($expectedDateString);

        $this->assertEquals(
            $timestamp,
            DateTime::dateTimeToTimestamp($dateTime)
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
        ];
    }

    /**
     * @covers ::createFromFormat
     */
    public function testCreateFromFormatInvalid()
    {
        $this->expectException(\Yoti\Exception\DateTimeException::class);
        $this->expectExceptionMessage('Could not parse from format');

        DateTime::createFromFormat('some-invalid-format', 'some-invalid-time');
    }

    /**
     * @covers ::stringToDateTime
     *
     * @dataProvider validDateStringProvider
     */
    public function testStringToDateTime($timeStampString, $expectedOutput)
    {
        $dateTime = DateTime::stringToDateTime($timeStampString);

        $this->assertEquals(
            $expectedOutput,
            $dateTime->format(DateTime::RFC3339)
        );
    }

    /**
     * Provides valid dates and their expected RFC3339 representation with microseconds.
     */
    public function validDateStringProvider()
    {
        return [
            [ '2006-01-02', '2006-01-02T00:00:00.000000+00:00' ],
            [ '2006-01-02T22:04:05Z', '2006-01-02T22:04:05.000000+00:00' ],
            [ '2006-01-02T22:04:05.1Z', '2006-01-02T22:04:05.100000+00:00' ],
            [ '2006-01-02T22:04:05.12Z', '2006-01-02T22:04:05.120000+00:00' ],
            [ '2006-01-02T22:04:05.123Z', '2006-01-02T22:04:05.123000+00:00' ],
            [ '2006-01-02T22:04:05.1234Z', '2006-01-02T22:04:05.123400+00:00' ],
            [ '2006-01-02T22:04:05.12345Z', '2006-01-02T22:04:05.123450+00:00' ],
            [ '2006-01-02T22:04:05.123456Z', '2006-01-02T22:04:05.123456+00:00' ],
            [ '2002-10-02T10:00:00-05:00', '2002-10-02T10:00:00.000000-05:00' ],
            [ '2002-10-02T10:00:00+11:00', '2002-10-02T10:00:00.000000+11:00' ],
            [ '1920-03-13T19:50:53.000001Z', '1920-03-13T19:50:53.000001+00:00' ],
            [ '1920-03-13T19:50:53.999999Z', '1920-03-13T19:50:53.999999+00:00' ],
            [ '1920-03-13T19:50:53.000100Z', '1920-03-13T19:50:53.000100+00:00'  ],
            [ '1920-03-13T19:50:53.999999+04:00', '1920-03-13T19:50:53.999999+04:00' ],
        ];
    }

    /**
     * @covers ::stringToDateTime
     */
    public function testInvalidTimestamp()
    {
        $this->expectException(\Yoti\Exception\DateTimeException::class);
        $this->expectExceptionMessage('Could not parse string to DateTime');

        DateTime::stringToDateTime('some-invalid-date');
    }

    /**
     * @covers ::stringToDateTime
     *
     * @dataProvider emptyTimestampProvider
     */
    public function testEmptyTimestamp($emptyDateString, $exceptionMessage, $type)
    {
        $this->expectException($type);
        $this->expectExceptionMessage($exceptionMessage);

        DateTime::stringToDateTime($emptyDateString);
    }

    /**
     * Provides empty timestamps
     */
    public function emptyTimestampProvider()
    {
        return [
            [ null, sprintf('%s::stringToDateTime()', DateTime::class), \TypeError::class ],
            [ '', 'value cannot be empty', \InvalidArgumentException::class ],
        ];
    }
}
