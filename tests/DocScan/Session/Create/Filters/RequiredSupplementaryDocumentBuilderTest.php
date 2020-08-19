<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\RequiredDocument;
use Yoti\DocScan\Session\Create\Filters\RequiredSupplementaryDocument;
use Yoti\DocScan\Session\Create\Filters\RequiredSupplementaryDocumentBuilder;
use Yoti\DocScan\Session\Create\Objective\Objective;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\RequiredSupplementaryDocumentBuilder
 */
class RequiredSupplementaryDocumentBuilderTest extends TestCase
{
    private const SUPPLEMENTARY_DOCUMENT = 'SUPPLEMENTARY_DOCUMENT';

    private const SOME_DOCUMENT_TYPES = ['SOME', 'DOCUMENT', 'TYPES'];
    private const SOME_COUNTRY_CODES = ['SOME', 'COUNTRY', 'CODES'];

    /**
     * @var Objective
     */
    private $objectiveMock;

    public function setup(): void
    {
        parent::setup();

        $this->objectiveMock = $this->createMock(Objective::class);
        $this->objectiveMock->method('jsonSerialize')->willReturn((object) ['some' => 'objective']);
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers ::withObjective
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredSupplementaryDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredSupplementaryDocument::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::jsonSerialize
     */
    public function shouldBuildRequiredSupplementaryDocument()
    {
        $requiredDocument = (new RequiredSupplementaryDocumentBuilder())
            ->withObjective($this->objectiveMock)
            ->build();

        $this->assertInstanceOf(RequiredDocument::class, $requiredDocument);
        $this->assertInstanceOf(RequiredSupplementaryDocument::class, $requiredDocument);

        $this->assertJsonStringEqualsJsonString(
            json_encode((object)[
                'type' => self::SUPPLEMENTARY_DOCUMENT,
                'objective' => $this->objectiveMock,
            ]),
            json_encode($requiredDocument)
        );
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers ::withDocumentTypes
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredSupplementaryDocument::jsonSerialize
     */
    public function shouldBuildRequiredSupplementaryDocumentWithDocumentTypes()
    {
        $requiredDocument = (new RequiredSupplementaryDocumentBuilder())
            ->withObjective($this->objectiveMock)
            ->withDocumentTypes(self::SOME_DOCUMENT_TYPES)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode((object)[
                'type' => self::SUPPLEMENTARY_DOCUMENT,
                'objective' => $this->objectiveMock,
                'document_types' => self::SOME_DOCUMENT_TYPES
            ]),
            json_encode($requiredDocument)
        );
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers ::withCountryCodes
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredSupplementaryDocument::jsonSerialize
     */
    public function shouldBuildRequiredSupplementaryDocumentWithCountryCodes()
    {
        $requiredDocument = (new RequiredSupplementaryDocumentBuilder())
            ->withObjective($this->objectiveMock)
            ->withCountryCodes(self::SOME_COUNTRY_CODES)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode((object)[
                'type' => self::SUPPLEMENTARY_DOCUMENT,
                'objective' => $this->objectiveMock,
                'country_codes' => self::SOME_COUNTRY_CODES
            ]),
            json_encode($requiredDocument)
        );
    }

    /**
     * @test
     *
     * @covers ::build
     */
    public function shouldThrowWithoutObjective()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('objective cannot be null');

        (new RequiredSupplementaryDocumentBuilder())->build();
    }

    /**
     * @test
     *
     * @covers ::withDocumentTypes
     */
    public function withDocumentTypesShouldThrowWithInvalidArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('documentTypes must be array of strings');

        (new RequiredSupplementaryDocumentBuilder())->withDocumentTypes([[]]);
    }

    /**
     * @test
     *
     * @covers ::withCountryCodes
     */
    public function withCountryCodesShouldThrowWithInvalidArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('countryCodes must be array of strings');

        (new RequiredSupplementaryDocumentBuilder())->withCountryCodes([[]]);
    }
}
