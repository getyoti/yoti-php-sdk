<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\DocumentFilter;
use Yoti\DocScan\Session\Create\Filters\Provider;
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

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::isAllowDigitalIds
     */
    public function shouldSerializeWithAllowDigitalIds()
    {
        $documentFilter = $this->getMockBuilder(DocumentFilter::class)
            ->setConstructorArgs([self::SOME_TYPE, true])
            ->setMethodsExcept(['jsonSerialize', 'isAllowDigitalIds'])
            ->getMock();

        $this->assertTrue($documentFilter->isAllowDigitalIds());

        $this->assertJsonStringEqualsJsonString(
            json_encode((object) [
                'type' => self::SOME_TYPE,
                'allow_digital_ids' => true,
            ]),
            json_encode($documentFilter)
        );
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::getAllowedProviders
     */
    public function shouldSerializeWithAllowedProviders()
    {
        $provider = new Provider('DIGILOCKER');

        $documentFilter = $this->getMockBuilder(DocumentFilter::class)
            ->setConstructorArgs([self::SOME_TYPE, null, [$provider]])
            ->setMethodsExcept(['jsonSerialize', 'getAllowedProviders'])
            ->getMock();

        $this->assertSame([$provider], $documentFilter->getAllowedProviders());

        $this->assertJsonStringEqualsJsonString(
            json_encode((object) [
                'type' => self::SOME_TYPE,
                'allowed_providers' => [$provider],
            ]),
            json_encode($documentFilter)
        );
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::getAllowedProviders
     */
    public function shouldSerializeAllowedProvidersAsJsonArrayWithNonSequentialKeys()
    {
        $documentFilter = $this->getMockBuilder(DocumentFilter::class)
            ->setConstructorArgs([
                self::SOME_TYPE,
                null,
                [3 => new Provider('DIGILOCKER'), 7 => new Provider('EPHIL_ID_QR')],
            ])
            ->setMethodsExcept(['jsonSerialize', 'getAllowedProviders'])
            ->getMock();

        $this->assertSame([0, 1], array_keys($documentFilter->getAllowedProviders()));
        $this->assertSame(
            '{"type":"some-type","allowed_providers":[{"name":"DIGILOCKER"},{"name":"EPHIL_ID_QR"}]}',
            json_encode($documentFilter)
        );
    }
}
