<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\DocumentFilter;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\DocumentFilter
 */
class DocumentFilterTest extends TestCase
{
    private const SOME_TYPE = 'some-type';

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::jsonSerialize
     */
    public function shouldSerializeWithType()
    {
        $documentFilter = $this->getMockBuilder(DocumentFilter::class)
            ->setConstructorArgs([self::SOME_TYPE])
            ->setMethodsExcept(['jsonSerialize'])
            ->getMock();

        $this->assertJsonStringEqualsJsonString(
            json_encode((object) [
                'type' => self::SOME_TYPE,
            ]),
            json_encode($documentFilter)
        );
    }
}
