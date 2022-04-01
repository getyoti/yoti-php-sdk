<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\RequiredDocument;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\RequiredDocument
 */
class RequiredDocumentTest extends TestCase
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
        $requiredDocument = $this->getMockBuilder(RequiredDocument::class)
            ->setConstructorArgs([self::SOME_TYPE])
            ->setMethodsExcept(['jsonSerialize'])
            ->getMock();

        $this->assertJsonStringEqualsJsonString(
            json_encode((object) [
                'type' => self::SOME_TYPE,
            ]),
            json_encode($requiredDocument)
        );
    }
}
